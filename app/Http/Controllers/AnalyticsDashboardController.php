<?php

namespace App\Http\Controllers;

use App\DataTables\ManageSiteDataTable;
use App\Models\Accesstoken;
use Google_Service_AnalyticsReporting;
use App\Models\Credential;
use Google_Client;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AnalyticsDashboardController extends Controller
{
    public function AnalyticsDashboard()
    {
        $user = Auth::user();
        if ((Auth::user()->type == 'Admin')) {
            $site = Site::where('created_by', $user->id)->get();
            return view('analytics.analytics')->with('site', $site);
        } else {
            $site = Site::where('created_by', $user->created_by)->get();
            return view('analytics.analytics')->with('site', $site);
        }
    }

    public function manageSite(ManageSiteDataTable $dataTable)
    {
        return Auth::user()->can('manage-analytics-dashboard') ? $dataTable->render('site.manage-site') : redirect()->back()->with('failed', __('Permission denied.'));
    }

    public function addSite()
    {
        $client = $this->authmain();
        if (Session::get("access_token") != '' && !empty(Session::get("access_token"))) {
            $token = Session::get('access_token');
            $user  = Auth::user();
            Accesstoken::updateOrCreate(
                ['created_by' => $user->id, 'name' => 'admin_access_token'],
                ['value'      => $token['access_token']]
            );
            Accesstoken::updateOrCreate(
                ['created_by' => $user->id, 'name' => 'admin_refresh_token'],
                ['value'      => $token['refresh_token'] ?? '']
            );

            $client->setAccessToken(Session::get("access_token"));
            $analytics = new Google_Service_AnalyticsReporting($client);

            $account   = $this->getProfiles($analytics);
            if ($account['is_success'] == 'true') {
                return view('site.add-site')->with('account', $account['data']);
            } else {
                return redirect()->route('analytics.dashboard')->with('errors', __('No accounts found for this user.'));
            }
        } else {
            return redirect()->route('oauth2callback');
        }
    }

    public function oauth2callback()
    {
        $client = $this->authmain();
        $code = $_GET['code'] ?? null;

        if (!$code) {
            return redirect(filter_var($client->createAuthUrl(), FILTER_SANITIZE_URL));
        }

        $client->authenticate($code);
        Session::put('access_token', $client->getAccessToken());
        return redirect()->route('analytics.add.site')->with('success', 'Authenticated successfully.');
    }

    function refreshAccessTokenWithGoogleClient()
    {
        $user             = Auth::user();
        $credentials      = Credential::where('user_id', $user->type == 'Admin' ? $user->id : $user->created_by)->value('json');
        $credentialsArray = json_decode($credentials, true);
        $clientId         = $credentialsArray['web']['client_id'] ?? null;
        $clientSecret     = $credentialsArray['web']['client_secret'] ?? null;

        if (!$clientId || !$clientSecret) {
            return redirect()->back()->with('failed', __('Invalid client credentials'));
            throw new \Exception('Invalid client credentials.');
        }
        $createdBy    = $user->type == 'Admin' ? $user->id : $user->created_by;
        $refreshToken = Accesstoken::where('created_by', $createdBy)->where('name', 'admin_refresh_token')->value('value');
        $accessToken  = Accesstoken::where('created_by', $createdBy)->where('name', 'admin_access_token')->value('value');

        if (!$refreshToken) {
            return redirect()->back()->with('failed', __('refresh token not found.'));
            throw new \Exception('refresh token not found.');
        }

        try {
            $client = new Google_Client();
            $client->setClientId($clientId);
            $client->setClientSecret($clientSecret);
            if ($refreshToken && !$accessToken || $client->isAccessTokenExpired()) {
                $client->refreshToken($refreshToken);
                $newAccessToken = $client->getAccessToken();
                $accessToken    = $newAccessToken['access_token'];
                $refreshToken   = $newAccessToken['refresh_token'];
                if ($user) {
                    Site::where('created_by', $createdBy)->update(['accessToken' => $accessToken]);
                    Accesstoken::where('created_by', $createdBy)->whereIn('name', 'admin_access_token')->update(['value' => $accessToken]);
                    Accesstoken::where('created_by', $createdBy)->where('name', 'admin_refresh_token')->update(['value' => $refreshToken]);
                }
            }
            Session::put('access_token', $newAccessToken);
        } catch (\Exception $e) {
            return redirect()->back()->with('failed', __('Failed to refresh the access token:') . $e->getMessage());
            throw new \Exception('Failed to refresh the access token: ' . $e->getMessage());
        }
    }

    public function saveJson(Request $request)
    {
        $request->validate([
            'json_file' => 'required|file|mimes:json|max:2048',
        ]);

        $user = Auth::user();
        if ($user->type == "Admin") {
            if ($request->has('json_file')) {
                $store = Credential::updateOrCreate(
                    ['user_id' => $user->id],
                    ['json'    => $request->file('json_file')->getContent()]
                );
                if ($store) {
                    $user->update(['is_json_upload' => 1]);
                    return redirect()->route('analytics.dashboard')->with('success', __('Your Credential has been saved'));
                }
                return redirect()->back()->with('errors', __('Something went wrong.'));
            } else {
                return redirect()->back()->with('errors', __('Something want worng.'));
            }
        } else {
            return redirect()->back()->with('errors', __('Permission Denied.'));
        }
    }

    public function authmain()
    {
        $user             = Auth::user();
        $credentials      = Credential::where('user_id', $user->type == 'Admin' ? $user->id : $user->created_by)->first();
        $credentialsArray = json_decode($credentials->json, true);
        $client           = new Google_Client();
        $client->setAuthConfig($credentialsArray);
        $client->addScope(\Google_Service_Analytics::ANALYTICS_READONLY);
        $client->setAccessType('offline');
        $client->setRedirectUri(route('oauth2callback'));

        return $client;
    }

    function getProfiles()
    {
        $token = Session::get("access_token");
        if (!$token || !isset($token['access_token'])) {
            $arrResult['is_success'] = false;
            $arrResult['data']   = __('Access token is missing or invalid');
        }
        $curl  = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => 'https://analyticsadmin.googleapis.com/v1beta/accounts',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => array(
                'Authorization: Bearer ' . $token['access_token']
            ),
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Get HTTP status code
        curl_close($curl);

        $res_data = json_decode($response, true);
        if ($res_data === null && json_last_error() !== JSON_ERROR_NONE) {
            $arrResult['is_success'] = false;
            $arrResult['data']   = 'Invalid JSON response.';
        }
        $arrResult = [];

        if ($httpCode !== 200 || isset($res_data['error'])) {
            if (isset($res_data['error']['code']) && $res_data['error']['code'] === 401) {
                $this->refreshAccessTokenWithGoogleClient();
                return $this->getProfiles();
            }
            error_log('Error fetching accounts: ' . json_encode($res_data));

            $arrResult['is_success'] = false;
            $arrResult['data']       = $res_data['error']['message'] ?? 'An unknown error occurred.';
        } else {
            if (isset($res_data['accounts']) && is_array($res_data['accounts'])) {
                $accounts = [];
                foreach ($res_data['accounts'] as $item) {
                    $temp = explode('/', $item['name']);
                    $accounts[] = [
                        'id'   => $temp[1],
                        'name' => $item['displayName']
                    ];
                }
                $arrResult['is_success'] = true;
                $arrResult['data']       = $accounts;
            } else {
                $arrResult['is_success'] = false;
                $arrResult['data']       = 'No accounts found for this user.';
            }
        }

        return $arrResult;
    }

    function getProperty(Request $request)
    {
        $accessToken = Session::get("access_token")['access_token'] ?? null;

        $account_id  = $request->account_id;
        if (!$account_id) {
            return response()->json(['errors' => 'Account ID is required'], 400);
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => 'https://analyticsadmin.googleapis.com/v1beta/properties?filter=parent:accounts/' . $account_id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'GET',
            CURLOPT_HTTPHEADER     => array(
                'Authorization: Bearer ' . $accessToken . ''
            ),
        ));

        $response  = curl_exec($curl);
        curl_close($curl);
        $res_data  = json_decode($response);
        $arrResult = [];
        if (!isset($res_data->error)) {
            if (isset($res_data->properties)) {
                foreach ($res_data->properties as $item) {
                    $temp         = explode('/', $item->name);
                    $data         = [];
                    $data['id']   = $temp[1];
                    $data['name'] = $item->displayName;
                    $ids[]        = $data;
                }
                $arrResult['is_success'] = true;
                $arrResult['data']       = $ids;
            } else {
                $arrResult['is_success'] = false;
                $arrResult['data']       = 'No Data Found.!';
            }
        } else {
            $this->refreshAccessTokenWithGoogleClient();
            $this->getProperty($request);
        }
        $output = '<option selected disabled>' . __('Select Property Id') . '</option>';
        foreach ($arrResult['data'] as $value) {
            $output .= '<option value="' . $value['id'] . '" data-id="' . $value['name'] . '">' . $value['id'] . ' - ' . $value['name'] . '</option>';
        }

        return $output;
    }

    public function saveSite(Request $request)
    {
        $data = $request->all();
        if (isset($data['site_name'])) {
            $parts = explode(' - ', $data['site_name']);
            $request['site_name'] = trim($parts[1] ?? $data['site_name']);
        }
        $user = Auth::user();
        $request->validate([
            'account_id'  => 'required|numeric|not_in:Select Account Id',
            'property_id' => 'required|numeric|not_in:Select Property Id',
        ], [
            'account_id.required'  => 'The account ID field is required.',
            'account_id.numeric'   => 'The account ID must be a number.',
            'account_id.not_in'    => 'The account ID field is required.',
            'property_id.required' => 'The property ID field is required.',
            'property_id.not_in'   => 'The property ID field is required.',
        ]);
        $account_id  = $request->account_id;
        $property_id = $request->property_id;
        if (Session::get("access_token") != "" && !empty(Session::get('access_token'))) {
            if ($user->type == "Admin") {
                $site_check = Site::where('property_id', $property_id)->where('created_by', $user->id)->first();
            } else {
                $site_check = Site::where('property_id', $property_id)->where('created_by', $user->created_by)->first();
            }

            if ($site_check) {
                return redirect()->back()->with('errors', __('Site is already exist'));
            } else {
                $accessToken  = Accesstoken::where('created_by', $user->id)->where('name', 'admin_access_token')->first();
                $refreshToken = Accesstoken::where('created_by', $user->id)->where('name', 'admin_refresh_token')->first();

                $site                = new Site();
                $site->account_id    = $account_id;
                $site->site_name     = $request->site_name;
                $site->property_id   = $property_id;
                $site->property_name = $request->property_name ? $request->property_name : null;
                $site->accessToken   = $accessToken->value;
                $site->refreshToken  = $refreshToken->value;
                if ($user->type == "Admin") {
                    $site->created_by = $user->id;
                } else {
                    $site->created_by = $user->created_by;
                }
                $site->save();

                return redirect()->route('analytics.dashboard')->with('success', __('Site created successfully..'));
            }
        } else {
            return redirect()->back()->with('errors', __('Session is expired.'));
        }
    }

    public function getChartData(Request $request)
    {
        $duration = $request->chart_duration;
        $type     = $request->type;
        $this->refreshAccessTokenWithGoogleClient();
        $site     = Site::where('id', $request->siteid)->first();
        $arrParam = $this->getDurationFromText($duration);

        if ($site) {
            try {
                if ($type == 'get_user_data') {
                    $metrics = 'activeUsers';
                    $request_json = '{"dimensions":[{"name":"' . $arrParam['dimension'] . '"}],"metrics":[{"name":"' . $metrics . '"}],"dateRanges":[{"startDate":"' . $arrParam['StartDate'] . '","endDate":"' . $arrParam['EndDate'] . '"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"' . $arrParam['dimension'] . '"}}],"keepEmptyRows":true}';
                }
                if ($type == 'bounceRateChart') {
                    $metrics = 'bounceRate';
                    $request_json = '{"dimensions":[{"name":"' . $arrParam['dimension'] . '"}],"metrics":[{"name":"' . $metrics . '"}],"dateRanges":[{"startDate":"' . $arrParam['StartDate'] . '","endDate":"' . $arrParam['EndDate'] . '"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"' . $arrParam['dimension'] . '"}}],"keepEmptyRows":true}';
                }
                if ($type == "sessionDuration") {
                    $metrics = 'averageSessionDuration';
                    $request_json = '{"dimensions":[{"name":"' . $arrParam['dimension'] . '"}],"metrics":[{"name":"' . $metrics . '"}],"dateRanges":[{"startDate":"' . $arrParam['StartDate'] . '","endDate":"' . $arrParam['EndDate'] . '"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"' . $arrParam['dimension'] . '"}}],"keepEmptyRows":true}';
                }
                if ($type == 'session_by_device') {
                    $metrics = 'averageSessionDuration';
                    $request_json = '{"dimensions":[{"name":"deviceCategory"}],"metrics":[{"name":"sessions"}],"dateRanges":[{"startDate":"2022-01-01","endDate":"2023-07-19"}],"keepEmptyRows":true,"metricAggregations":["TOTAL"]}';
                }
                if ($type == "user-timeline-chart") {
                    $request_json = '{"dimensions":[{"name":"' . $arrParam['dimension'] . '"}],"metrics":[{"name":"activeUsers"},{"name":"newUsers"}],"dateRanges":[{"startDate":"' . $arrParam['StartDate'] . '","endDate":"' . $arrParam['EndDate'] . '"}],"orderBys":[{"dimension":{"orderType":"NUMERIC","dimensionName":"' . $arrParam['dimension'] . '"}}],"keepEmptyRows":true}';
                }
                if ($type == "mapcontainer") {
                    $request_json = '{"dimensions":[{"name":"country"}],"metrics":[{"name":"activeUsers"}],"dateRanges":[{"startDate":"' . $arrParam['StartDate'] . '","endDate":"' . $arrParam['EndDate'] . '"}],"keepEmptyRows":true}';
                }

                $data = $this->getReport($site, $request_json);

                $label = [];
                $dataset = [];
                $sum = 0;
                $arrReturn             = [];
                if (!isset($data->error)) {
                    if ($type == 'user-timeline-chart') {
                        $tempfirst = [];
                        $sum_tempfirst = 0;
                        $tempsecond = [];
                        $sum_tempsecond = 0;
                        foreach ($arrParam['arrField'] as $ke => $val) {
                            if (isset($data->rows) && !empty($data->rows)) {
                                foreach ($data->rows as $key => $value) {
                                    if ($data->rows[$key]->dimensionValues[0]->value == $ke) {
                                        $res_data_first = $data->rows[$key]->metricValues[0]->value;
                                        $sum_tempfirst += $data->rows[$key]->metricValues[0]->value;
                                        $res_data_second = $data->rows[$key]->metricValues[1]->value;
                                        $sum_tempsecond += $data->rows[$key]->metricValues[1]->value;
                                        break;
                                    } else {
                                        $res_data_first = 0;
                                        $res_data_second = 0;
                                    }
                                }
                            } else {
                                $res_data_first = 0;
                                $res_data_second = 0;
                            }
                            $label[] = $val;
                            $tempfirst[] = (int)$res_data_first;
                            $tempsecond[] = (int)$res_data_second;
                        }
                        $dataset[0] = array("label" => "Active Users", "data" => array_reverse($tempfirst), "backgroundColor" => "transparent", "borderColor" => "#5e72e4", "borderWidth" => 4, "tension" => 0.4);
                        $dataset[1] = array("label" => "New Users", "data" => array_reverse($tempsecond), "backgroundColor" => "transparent", "borderColor" => "#11cdef", "borderWidth" => 4, "tension" => 0.4);
                        $arrResult['total']      = array("Active_Users" => $sum_tempfirst, "New_Users" => $sum_tempsecond);

                        $arrReturn['labels']   = array_reverse($label);
                        $arrReturn['datasets']   = $dataset;
                    } elseif ($type == 'session_by_device') {

                        if (isset($data->rows) && !empty($data->rows)) {
                            $total = $data->totals[0]->metricValues[0]->value;
                            foreach ($data->rows as $key => $value) {

                                $label[] = ucfirst($data->rows[$key]->dimensionValues[0]->value);
                                $res_data = ($data->rows[$key]->metricValues[0]->value * 100) / $total;
                                $dataset[] = (int)number_format($res_data, 0);
                            }
                        } else {
                            $label[] = "No data found";
                            $res_data = 100;
                            $dataset[] = (int)number_format($res_data, 0);
                        }

                        $arrReturn['labels']   = $label;
                        $arrReturn['datasets']   = $dataset;
                        $arrResult['total']      = $sum;
                    } elseif ($type == 'mapcontainer') {
                        if (isset($data->rows) && !empty($data->rows)) {

                            foreach ($data->rows as $key => $value) {
                                $temp = array();

                                $temp[] = $value->dimensionValues[0]->value;
                                $temp[] = $value->metricValues[0]->value;
                                $dataset[] = $temp;
                            }
                            $arrReturn['labels'] = $label;
                            $arrReturn['datasets'] = $dataset;
                        }
                    } else {
                        foreach ($arrParam['arrField'] as $ke => $val) {
                            if (isset($data->rows) && !empty($data->rows)) {
                                foreach ($data->rows as $key => $value) {
                                    if ($data->rows[$key]->dimensionValues[0]->value == $ke) {
                                        $res_data = $data->rows[$key]->metricValues[0]->value;
                                        $sum += $data->rows[$key]->metricValues[0]->value;
                                        if ($type == 'bounceRateChart') {
                                            $res_data = $res_data * 100;
                                            $res_data = round($res_data);
                                        }
                                        if ($type == 'sessionDuration') {
                                            $res_data = round($res_data);
                                        }
                                        break;
                                    } else {

                                        $res_data = 0;
                                    }
                                }
                            } else {
                                $res_data = 0;
                            }

                            $label[] = $val;
                            $dataset[] = $res_data;
                        }

                        $arrReturn['labels']   = array_reverse($label);
                        $arrReturn['datasets']   = array_reverse($dataset);
                        $arrResult['total']      = $sum;
                    }

                    $arrResult['is_success'] = 1;
                    $arrResult['data']       = $arrReturn;
                } else {
                    if ($data->error->code == 401) {
                        $this->getChartData($request);
                    }
                    $arrReturn               = [];
                    $arrReturn['labels']     = [];
                    $arrReturn['datasets']   = [];
                    $arrResult['is_success'] = 0;
                    $arrResult['message']    = $data->error->message;
                    $arrResult['data']       = $arrReturn;
                    $arrResult['total']      = 0;
                    $arrResult['link']       = '';
                }
            } catch (Exception $e) {
                $arrReturn               = [];
                $arrReturn['labels']     = [];
                $arrReturn['datasets']   = [];
                $arrResult['is_success'] = 0;
                $arrResult['message']    = $e;
                $arrResult['data']       = $arrReturn;
                $arrResult['total']      = 0;
                $arrResult['link']       = '';
            }
        } else {
            $arrReturn               = [];
            $arrReturn['labels']     = [];
            $arrReturn['datasets']   = [];
            $arrResult['is_success'] = 0;
            $arrResult['message']    = __('Site note found');
            $arrResult['data']       = $arrReturn;
            $arrResult['total']      = 0;
            $arrResult['link']       = '';
        }

        return $arrResult;
    }

    function getReport($site, $request_json)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL            => 'https://analyticsdata.googleapis.com/v1beta/properties/' . $site->property_id . ':runReport',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $request_json,
            CURLOPT_HTTPHEADER     => array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $site->accessToken . ''
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $res_data = json_decode($response);
        return $res_data;
    }

    function getDurationFromText($duration)
    {
        $arrDate  = [];
        $arrField = [];
        $duration = strtolower($duration);

        if ($duration == "today") {
            $arrDate['dimension'] = "hour";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = date('Y-m-d');
            for ($i = 0; $i <= 23; $i++) {
                if ($i <= 9) {
                    $arrField['0' . $i] = $i;
                } else {
                    $arrField[$i] = $i;
                }
            }

            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        } elseif ($duration == "yesterday") {
            $arrDate['dimension'] = "date";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = date('Y-m-d');
            for ($i = 1; $i <= 2; $i++) {
                $startDate                                  = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
                $arrField[date('l', strtotime($startDate))] = date('l', strtotime($startDate));
            }

            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        } elseif ($duration == "week" || $duration == "7daysago") {
            $arrDate['dimension'] = "dayOfWeekName";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = $arrDate['EndDate'];
            for ($i = 1; $i <= 7; $i++) {
                $startDate                                  = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
                $arrField[date('l', strtotime($startDate))] = date('l', strtotime($startDate));
            }
            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        } elseif ($duration == "15daysago") {
            $arrDate['dimension'] = "date";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = $arrDate['EndDate'];
            for ($i = 1; $i <= 15; $i++) {
                $arrField[date('Ymd', strtotime($startDate))] = date('d-m-Y', strtotime($startDate));
                $startDate                                    = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
            }
            $arrField[date('Ymd', strtotime($startDate))] = date('d-m-Y', strtotime($startDate));

            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        } elseif ($duration == "month" || $duration == "30daysago") {
            $arrDate['dimension'] = "date";
            $arrDate['EndDate']   = date('Y-m-d');
            $startDate            = $arrDate['EndDate'];

            for ($i = 1; $i <= 30; $i++) {
                $startDate                                    = date('Y-m-d', strtotime('-1 day', strtotime($startDate)));
                $arrField[date('Ymd', strtotime($startDate))] = date('d-m-Y', strtotime($startDate));
            }
            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        } elseif ($duration == "year") {

            $arrDate['dimension'] = "yearMonth";
            $arrDate['EndDate']   = date('Y-m-d', strtotime('+1 month', time()));
            $startDate            = $arrDate['EndDate'];
            for ($i = 1; $i <= 12; $i++) {
                $startDate                                   = date('Y-m-d', strtotime('-1 month', strtotime($startDate)));
                $arrField[date('Ym', strtotime($startDate))] = date('M', strtotime($startDate));
            }
            $arrDate['StartDate'] = $startDate;
            $arrDate['arrField']  = $arrField;
        }

        return $arrDate;
    }

    public function getLiveUsers(Request $request)
    {
        $site      = Site::where('id', $request->siteid)->first();
        $arrResult = $this->getLiveUser($site);
        $res       = json_encode($arrResult);
        return $res;
    }

    function getLiveUser($objSite)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://analyticsdata.googleapis.com/v1beta/properties/' . $objSite->property_id . ':runRealtimeReport',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode([
                "metrics" => [
                    ["name" => "activeUsers"]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $objSite->accessToken
            ],
        ]);

        $response  = curl_exec($curl);
        $httpCode  = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            return [
                'is_success' => 0,
                'message'    => 'CURL Error: ' . $curlError
            ];
        }
        $res_data = json_decode($response);
        if ($httpCode === 200 && isset($res_data->rows)) {
            return $res = [
                'is_success' => 1,
                'liveUser'   => $res_data->rows[0]->metricValues[0]->value
            ];
        } elseif (isset($res_data->error)) {
            return $res = [
                'is_success' => 0,
                'message'    => $res_data->error->message ?? 'Unknown error occurred'
            ];
        } else {
            return $res = [
                'is_success' => 0,
                'message'    => 'No data found'
            ];
        }
    }

    public function activePage(Request $request)
    {
        $siteid   = $request->get('siteid');
        $site     = Site::where("id", $siteid)->first();
        $arrParam = $this->getDurationFromText('month');

        $request_json = '{"dimensions":[{"name":"pagePath"}],"metrics":[{"name":"screenPageViews"},{"name":"screenPageViewsPerUser"}],"dateRanges":[{"startDate":"' . $arrParam['StartDate'] . '","endDate":"' . $arrParam['EndDate'] . '"}],"keepEmptyRows":true}';
        $data         = $this->getReport($site, $request_json);
        $arrData      = [];
        if (isset($data->rows) && !empty($data->rows)) {
            foreach ($data->rows as $key => $value) {
                $arrData[] = [
                    'PageUrl' => $data->rows[$key]->dimensionValues[0]->value,
                    'screenPageViews' => number_format($data->rows[$key]->metricValues[0]->value),
                    'screenPageViewsPerUser' => number_format($data->rows[$key]->metricValues[1]->value, 2),
                ];
            }
        }
        $arrResult['is_success'] = 1;
        $arrResult['data']       = $arrData;
        return $arrResult;
    }

    public function deleteSite($id)
    {
        $site = Site::find($id);
        $site->delete();
        return redirect()->route('analytics.Manage.site')->with('success', __('Site delete successfully..'));
    }

    public function destroyCredential($id)
    {
        if (Auth::user()->can('delete-analytics-dashboard')) {
            $user = User::find($id);
            Session::forget('access_token');
            Credential::where('user_id', $user->type == 'Admin' ? $user->id : $user->created_by)->delete();
            Accesstoken::where('created_by', $user->id)->delete();
            Site::where('created_by', $user->id)->delete();
            $user->update(['is_json_upload' => 0]);

            return redirect()->route('analytics.dashboard')->with('success', __('Credential deleted successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }
}
