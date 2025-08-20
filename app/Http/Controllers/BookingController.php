<?php

namespace App\Http\Controllers;

use App\DataTables\BookingDataTable;
use App\Facades\UtilityFacades;
use App\Mail\BookingSubmitEmail;
use App\Mail\BookingThanksmail;
use App\Models\Booking;
use App\Models\BookingValue;
use App\Models\NotificationsSetting;
use App\Models\SeatWiseBooking;
use App\Models\TimeWiseBooking;
use App\Models\User;
use App\Notifications\NewBookingSurveyDetails;
use Carbon\Carbon;
use Exception;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Spatie\MailTemplates\Models\MailTemplate;
use Stripe\Stripe as StripeStripe;

class BookingController extends Controller
{
    public function index(BookingDataTable $dataTable)
    {
        if (\Auth::user()->can('manage-booking')) {
            return $dataTable->render('bookings.index');
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create-booking')) {
            $bookingSlots['']                   = __('Select Booking Slots');
            $bookingSlots['time_wise_booking']  = 'Time Wise Booking';
            $bookingSlots['seats_wise_booking'] = 'Seats Wise Booking';
            return view('bookings.create', compact('bookingSlots'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('create-booking')) {
            request()->validate([
                'business_name'    => 'required|string|max:191',
                'business_email'   => 'required|email|max:191',
                'business_website' => 'required|url|max:191',
                'business_address' => 'required|string|max:191',
                'business_number'  => 'required|numeric',
                'business_phone'   => 'required|numeric',
                'booking_slots'    => 'required|string|max:191',
                'business_logo'    => 'required|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            $fileName = '';
            if ($request->hasFile('business_logo')) {
                $file       = $request->file('business_logo');
                $fileName   =  $file->store('booking');
            }
            Booking::create([
                'business_name'     => $request->business_name,
                'business_email'    => $request->business_email,
                'business_website'  => $request->business_website,
                'business_address'  => $request->business_address,
                'business_number'   => $request->business_number,
                'business_logo'     => $fileName,
                'business_phone'    => $request->business_phone,
                'booking_slots'     => $request->booking_slots,
            ]);
            return redirect()->route('bookings.index')->with('success', __('Booking created succesfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }
    public function edit($id)
    {
        if (\Auth::user()->can('edit-booking')) {
            $booking = Booking::find($id);
            return view('bookings.edit', compact('booking'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('edit-booking')) {
            request()->validate([
                'business_name'                 => 'required|string|max:191',
                'business_email'                => 'required|email|max:191',
                'business_website'              => 'required|url|max:191',
                'business_address'              => 'required|string|max:191',
                'business_number'               => 'required|numeric',
                'business_phone'                => 'required|numeric',
                'business_logo'                 => 'image|mimes:jpeg,jpg,png|max:2048',
            ]);
            $booking                            = Booking::find($id);
            if ($request->hasFile('business_logo')) {
                $file                           = $request->file('business_logo');
                $fileName                       =  $file->store('booking');
                $booking->business_logo         = $fileName;
            }
            $booking->business_name             = $request->business_name;
            $booking->business_email            = $request->business_email;
            $booking->business_website          = $request->business_website;
            $booking->business_address          = $request->business_address;
            $booking->business_number           = $request->business_number;
            $booking->business_phone            = $request->business_phone;
            $booking->created_by                = \Auth::user()->id;
            $booking->save();
            return redirect()->route('bookings.index')->with('success', __('Booking updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {
        if (\Auth::user()->can('delete-booking')) {
            $booking = Booking::find($id);
            if (File::exists(Storage::path($booking->business_logo))) {
                Storage::delete($booking->business_logo);
            }
            $booking->delete();
            return redirect()->back()->with('success', __('Booking deleted succesfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function design($id)
    {
        if (\Auth::user()->can('design-booking')) {
            $booking = Booking::find($id);
            return view('bookings.design', compact('booking'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function designUpdate(Request $request, $id)
    {
        if (\Auth::user()->can('design-booking')) {
            request()->validate([
                'json'              => 'required|json',
            ]);
            $booking                = Booking::find($id);
            $booking->json          = $request->json;
            $booking->created_by    = \Auth::user()->id;
            $booking->save();
            return redirect()->route('bookings.index')->with('success', __('Booking design updated successfully.'));
        } else {
            return redirect()->back()->with('failed', __('Permission denied.'));
        }
    }

    public function slotsSetting($id)
    {
        $booking = Booking::find($id);
        if ($booking->booking_slots == 'time_wise_booking') {
            $weekTimes['sunday']    = 'Sunday';
            $weekTimes['monday']    = 'Monday';
            $weekTimes['tuesday']   = 'Tuesday';
            $weekTimes['wednesday'] = 'Wednesday';
            $weekTimes['thursday']  = 'Thursday';
            $weekTimes['friday']    = 'Friday';
            $weekTimes['saturday']  = 'Saturday';
            $timeWiseBooking        = TimeWiseBooking::where('booking_id', $booking->id)->first();
            $selectedweektime       = [];
            if ($timeWiseBooking) {
                $selectedweektime   = explode(',', $timeWiseBooking->week_time);
            }
            return view('bookings.slots.time', compact('booking', 'weekTimes', 'selectedweektime', 'timeWiseBooking'));
        } else if ($booking->booking_slots == 'seats_wise_booking') {
            $weekTimes['sunday']    = 'Sunday';
            $weekTimes['monday']    = 'Monday';
            $weekTimes['tuesday']   = 'Tuesday';
            $weekTimes['wednesday'] = 'Wednesday';
            $weekTimes['thursday']  = 'Thursday';
            $weekTimes['friday']    = 'Friday';
            $weekTimes['saturday']  = 'Saturday';
            $seatWiseBooking        = SeatWiseBooking::where('booking_id', $booking->id)->first();
            $selectedweektime       = [];
            if ($seatWiseBooking) {
                $selectedweektime   = explode(',', $seatWiseBooking->week_time);
            }
            return view('bookings.slots.seat', compact('booking', 'weekTimes', 'selectedweektime', 'seatWiseBooking'));
        }
    }

    public function slotsSettingUpdate(Request $request, $id)
    {
        $booking = Booking::find($id);
        if ($booking->booking_slots == 'time_wise_booking') {
            request()->validate([
                'slot_duration'           => 'required|string|max:191',
                'services'                => 'required|string',
                'week_time'               => 'required|array',
                'slot_duration_minutes'   => 'nullable|integer',
                'interval_times'          => 'nullable|array',
                'start_date'              => 'nullable|date_format:d/m/Y',
                'end_date'                => 'nullable|date_format:d/m/Y',
                'rolling_days'            => 'nullable|integer|required_if:rolling_days_status,on',
                'maximum_limit'           => 'nullable|integer|required_if:maximum_limit_status,on',
                'multiple_booking_limit'  => 'nullable|integer|required_if:multiple_booking,on',
                'time_zone'               => 'required|string|max:191',
                'date_format'             => 'required|string|max:191',
                'time_format'             => 'required|string|max:191',
                'note'                    => 'nullable|string',
            ]);
            if ($request->slot_duration == 'custom_min') {
                $slottype       = $request->slot_duration;
                $slotDuration   = $request->slot_duration_minutes;
            } else {
                $slottype       = 'no-custom';
                $slotDuration   = $request->slot_duration;
            }
            TimeWiseBooking::updateorcreate(
                [
                    'booking_id'                        => $id
                ],
                [
                    'slot_duration'                     => $slottype,
                    'slot_duration_minutes'             => $slotDuration,
                    'services'                          => $request->services,
                    'week_time'                         => ($request->week_time) ? implode(',', $request->week_time) : null,
                    'intervals_time_status'             => ($request->intervals_time) ? 1 : 0,
                    'intervals_time_json'               => ($request->interval_times) ?  json_encode($request->interval_times) : null,
                    'limit_time_status'                 => ($request->limit_time_status) ? 1 : 0,
                    'start_date'                        => ($request->start_date) ? Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d') : null,
                    'end_date'                          => ($request->end_date) ? Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d') : null,
                    'rolling_days_status'               => ($request->rolling_days_status) ? 1 : 0,
                    'rolling_days'                      => ($request->rolling_days) ? $request->rolling_days : null,
                    'maximum_limit_status'              => ($request->maximum_limit_status) ? 1 : 0,
                    'maximum_limit'                     => ($request->maximum_limit) ? $request->maximum_limit : null,
                    'multiple_booking'                  => ($request->multiple_booking) ? 1 : 0,
                    'multiple_booking_limit'            => ($request->multiple_booking_limit) ? $request->multiple_booking_limit : 1,
                    'email_notification'                => ($request->email_notification) ? 1 : 0,
                    'time_zone'                         => $request->time_zone,
                    'date_format'                       => $request->date_format,
                    'time_format'                       => $request->time_format,
                    'enable_note'                       => ($request->enable_note) ? 1 : 0,
                    'note'                              => ($request->note) ? $request->note : null,
                ]
            );
        } elseif ($booking->booking_slots == 'seats_wise_booking') {
            request()->validate([
                'seat_booking'            => 'required|array',
                'services'                => 'required|string',
                'week_time'               => 'required|array',
                'session_times'           => 'nullable|array',
                'start_date'              => 'nullable|date_format:d/m/Y',
                'end_date'                => 'nullable|date_format:d/m/Y',
                'rolling_days'            => 'nullable|integer|required_if:rolling_days_status,on',
                'maximum_limit'           => 'nullable|integer|required_if:maximum_limit_status,on',
                'multiple_booking_limit'  => 'nullable|integer|required_if:multiple_booking,on',
                'time_zone'               => 'required|string|max:191',
                'date_format'             => 'required|string|max:191',
                'time_format'             => 'required|string|max:191',
                'note'                    => 'nullable|string',
            ]);
            SeatWiseBooking::updateorcreate(
                [
                    'booking_id'                        => $id
                ],
                [
                    'seat_booking_json'                 => json_encode($request->seat_booking),
                    'services'                          => $request->services,
                    'week_time'                         => ($request->week_time) ? implode(',', $request->week_time) : null,
                    'session_time_status'               => ($request->session_time) ? 1 : 0,
                    'session_time_json'                 => ($request->session_times) ?  json_encode($request->session_times) : null,
                    'limit_time_status'                 => ($request->limit_time_status) ? 1 : 0,
                    'start_date'                        => $request->start_date ? Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d') : null,
                    'end_date'                          => $request->end_date ? Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d') : null,
                    'rolling_days_status'               => ($request->rolling_days_status) ? 1 : 0,
                    'rolling_days'                      => ($request->rolling_days) ? $request->rolling_days : null,
                    'maximum_limit_status'              => ($request->maximum_limit_status) ? 1 : 0,
                    'maximum_limit'                     => ($request->maximum_limit) ? $request->maximum_limit : null,
                    'multiple_booking'                  => ($request->multiple_booking) ? 1 : 0,
                    'multiple_booking_limit'            => ($request->multiple_booking_limit) ? $request->multiple_booking_limit : 1,
                    'email_notification'                => ($request->email_notification) ? 1 : 0,
                    'time_zone'                         => $request->time_zone,
                    'date_format'                       => $request->date_format,
                    'time_format'                       => $request->time_format,
                    'enable_note'                       => ($request->enable_note) ? 1 : 0,
                    'note'                              => ($request->note) ? $request->note : null,
                ]
            );
        }
        return redirect()->route('booking.design', $booking->id)->with('success', __('Booking updated successfully.'));
    }

    public function appoinmentTime(Request $request, $id)
    {
        $booking                    = Booking::find($id);
        $timeWiseBooking            = TimeWiseBooking::where('booking_id', $booking->id)->first();
        if ($timeWiseBooking) {
            $timeZone               = $timeWiseBooking->time_zone;
            $currDate               = Carbon::now($timeZone);
            $bookingStartDate       = $currDate->format('Y-m-d');
            $bookingEndDate         = $currDate->copy()->endOfYear()->format('Y-m-d');
            if ($timeWiseBooking->limit_time_status == 1) {
                $bookingStartDate   = $timeWiseBooking->start_date;
                $bookingEndDate     = $timeWiseBooking->end_date;
            }
            if ($timeWiseBooking->rolling_days_status == 1) {
                $bookingStartDate   = $currDate->format('Y-m-d');
                $bookingEndDate     = $currDate->copy()->addDays($timeWiseBooking->rolling_days)->format('Y-m-d');
            }
            $dayMapping = [
                'sunday'    => 0,
                'monday'    => 1,
                'tuesday'   => 2,
                'wednesday' => 3,
                'thursday'  => 4,
                'friday'    => 5,
                'saturday'  => 6,
            ];
            $daysArray      = explode(',', $timeWiseBooking->week_time);
            $selectedDays   = array_map(function ($day) use ($dayMapping) {
                return $dayMapping[strtolower(trim($day))];
            }, $daysArray);
            $array          = json_decode($booking->json);
            return view('bookings.appoinment-time', compact('booking', 'timeWiseBooking', 'array', 'selectedDays', 'bookingStartDate', 'bookingEndDate', 'currDate'));
        } else {
            return redirect()->back()->with('errors', __('Please complete your booking proccess.'));
        }
    }

    public function getappoinmentSlot(Request $request, $id)
    {
        $booking                = Booking::find($id);
        $timeWiseBooking        = TimeWiseBooking::where('booking_id', $booking->id)->first();
        $timeZone               = $timeWiseBooking->time_zone;
        $currDate               = Carbon::now($timeZone);
        $bookingValues          = BookingValue::where('booking_id', $booking->id)->where('booking_slots_date', $request->date)->select('booking_slots_date', 'booking_slots')->get()->toArray();
        $timeBookinglimit       = 1;
        if ($timeWiseBooking->maximum_limit_status == 1) {
            $timeBookinglimit   = $timeWiseBooking->maximum_limit;
        }
        $timeArrays             = [];
        $timepartitions         = [];
        $bookingStartDate       = $currDate->format('Y-m-d');
        $bookingEndDate         = $currDate->copy()->endOfYear()->format('Y-m-d');
        if ($timeWiseBooking->limit_time_status == 1) {
            $bookingStartDate   = $timeWiseBooking->start_date;
            $bookingEndDate     = $timeWiseBooking->end_date;
        }
        if ($timeWiseBooking->rolling_days_status == 1) {
            $bookingStartDate   = $currDate->format('Y-m-d');
            $bookingEndDate     = $currDate->copy()->addDays($timeWiseBooking->rolling_days)->format('Y-m-d');
        }
        if ($request->date >= $bookingStartDate && $request->date <= $bookingEndDate) {
            $timeArrays[0]['start_time'] =  Carbon::parse($request->date . ' 00:00', $timeZone);
            $timeArrays[0]['end_time']   = Carbon::parse($request->date . ' 24:00', $timeZone);
            if ($timeWiseBooking->intervals_time_status == 1) {
                $timeIntervals           =  json_decode($timeWiseBooking->intervals_time_json, true);
                foreach ($timeIntervals as $timeIntervalKey => $timeInterval) {
                    $timeArrays[$timeIntervalKey]['start_time'] = Carbon::parse($request->date . ' ' . $timeInterval['start_time'], $timeZone);
                    $timeArrays[$timeIntervalKey]['end_time'] = Carbon::parse($request->date . ' ' . $timeInterval['end_time'], $timeZone);
                }
            }
        }
        foreach ($timeArrays as $timeArray) {
            while ($timeArray['start_time']->lt($timeArray['end_time'])) {
                $endTimeSlot        = $timeArray['start_time']->copy()->addMinutes($timeWiseBooking->slot_duration_minutes);
                $timepartitions[]   = [
                    'start' => $timeArray['start_time'],
                    'end'   => $endTimeSlot,
                ];
                $timeArray['start_time'] = $endTimeSlot;
            }
        }
        $html                       = '';
        $nearestSlotKey             = null;
        $firstSlotChecked = false;

        foreach ($timepartitions as $timepartitionKey => $timepartition) {
            $attribute       = '';
            $class           = '';
            $timeFormatValue = $timepartition["start"]->format('H:i') . '-' . $timepartition["end"]->format('H:i');
            $timeFormatLabel = ($timeWiseBooking->time_format == '24_hour')
                ? $timepartition['start']->format('H:i') . ' to ' . $timepartition['end']->format('H:i')
                : $timepartition['start']->format('h:i') . ' ' . $timepartition['start']->format('A') . ' to ' . $timepartition['end']->format('h:i') . ' ' . $timepartition['end']->format('A');
            if ($timepartition['start'] < $currDate || Self::isSlotBooked($bookingValues, $timeBookinglimit, $timeFormatValue)) {
                $attribute = 'disabled';
                $class     = 'disabled';
            }
            if (!$firstSlotChecked && !$attribute) {
                $attribute = 'checked';
                $firstSlotChecked = true;
            } elseif (!$request->slots && !$request->selecteddate) {
                $slots = explode(',', $request->slots);
                if (in_array($timeFormatValue, $slots) && $request->selecteddate >= $currDate->format('Y-m-d')) {
                    $attribute = 'checked';
                }
            }

            $inputType = $timeWiseBooking->multiple_booking == 1 ? 'checkbox' : 'radio';
            $inputName = $timeWiseBooking->multiple_booking == 1 ? 'slot[]' : 'slot';

            $html .= '<input class="btn-check ' . $class . '" id="slot_' . $timepartitionKey . '" name="' . $inputName . '" type="' . $inputType . '" value="' . $timeFormatValue . '" ' . $attribute . '>
            <label for="slot_' . $timepartitionKey . '" class="my-2 btn btn-outline-primary">' . $timeFormatLabel . '</label>';
        }
        return response()->json(['is_success' => 1, 'html' => $html]);
    }

    function isSlotBooked($bookingValues, $limitPerSlot, $slot)
    {
        $bookedCount        = 0;
        foreach ($bookingValues as $bookingValue) {
            $bookedSlots    = explode(',', $bookingValue['booking_slots']);
            if (in_array($slot, $bookedSlots)) {
                $bookedCount++;
                if ($bookedCount >= $limitPerSlot) {
                    return true;
                }
            }
        }
        return false;
    }

    public function appoinmentSeat(Request $request, $id)
    {
        $booking                    = Booking::find($id);
        $seatWiseBooking            = SeatWiseBooking::where('booking_id', $booking->id)->first();
        if ($seatWiseBooking) {
            $array                  = json_decode($booking->json);
            $timeZone               = $seatWiseBooking->time_zone;
            $currDate               = Carbon::now($timeZone);
            $bookingStartDate       = $currDate->format('Y-m-d');
            $bookingEndDate         = $currDate->copy()->endOfYear()->format('Y-m-d');
            if ($seatWiseBooking->limit_time_status == 1) {
                $bookingStartDate   = $seatWiseBooking->start_date;
                $bookingEndDate     = $seatWiseBooking->end_date;
            }
            if ($seatWiseBooking->rolling_days_status == 1) {
                $bookingStartDate   = $currDate->format('Y-m-d');
                $bookingEndDate     = $currDate->copy()->addDays($seatWiseBooking->rolling_days)->format('Y-m-d');
            }
            $dayMapping = [
                'sunday'    => 0,
                'monday'    => 1,
                'tuesday'   => 2,
                'wednesday' => 3,
                'thursday'  => 4,
                'friday'    => 5,
                'saturday'  => 6,
            ];
            $daysArray      = explode(',', $seatWiseBooking->week_time);
            $selectedDays   = array_map(function ($day) use ($dayMapping) {
                return $dayMapping[strtolower(trim($day))];
            }, $daysArray);
            return view('bookings.appoinment-seat', compact('booking', 'seatWiseBooking', 'array', 'selectedDays', 'bookingStartDate', 'bookingEndDate', 'currDate'));
        } else {
            return redirect()->back()->with('errors', __('Please complete your booking proccess.'));
        }
    }

    public function getappoinmentSeat(Request $request, $id)
    {
        $booking                            = Booking::find($id);
        $seatWiseBooking                    = SeatWiseBooking::where('booking_id', $booking->id)->first();
        $timeZone                           = $seatWiseBooking->time_zone;
        $currDate                           = Carbon::now($timeZone);
        $timeArrays                         = [];
        $timepartitions                     = [];
        $bookingStartDate                   = $currDate->format('Y-m-d');
        $bookingEndDate                     = $currDate->copy()->endOfYear()->format('Y-m-d');
        if ($seatWiseBooking->limit_time_status == 1) {
            $bookingStartDate               = $seatWiseBooking->start_date;
            $bookingEndDate                 = $seatWiseBooking->end_date;
        }
        if ($seatWiseBooking->rolling_days_status == 1) {
            $bookingStartDate               = $currDate->format('Y-m-d');
            $bookingEndDate                 = $currDate->copy()->addDays($seatWiseBooking->rolling_days)->format('Y-m-d');
        }
        if ($request->date >= $bookingStartDate && $request->date <= $bookingEndDate) {
            $timeArrays[0]['start_time']    = Carbon::parse($request->date . ' 00:00', $timeZone);
            $timeArrays[0]['end_time']      = Carbon::parse($request->date . ' 24:00', $timeZone);
            if ($seatWiseBooking->session_time_status == 1) {
                $timeIntervals              = json_decode($seatWiseBooking->session_time_json, true);
                foreach ($timeIntervals as $timeIntervalKey => $timeInterval) {
                    $timeArrays[$timeIntervalKey]['start_time'] = Carbon::parse($request->date . ' ' . $timeInterval['start_time'], $timeZone);
                    $timeArrays[$timeIntervalKey]['end_time']   = Carbon::parse($request->date . ' ' . $timeInterval['end_time'], $timeZone);
                }
            }
        }
        foreach ($timeArrays as $timeArray) {
            $timepartitions[] = [
                'start' => $timeArray['start_time'],
                'end' => $timeArray['end_time'],
            ];
        }
        $html = '';
        $firstAvailableSlotChecked = false;
        foreach ($timepartitions as $timepartitionKey => $timepartition) {
            $attribute = '';
            $class = '';
            if ($timepartition['start'] < $currDate) {
                $attribute = 'disabled';
                $class = 'disabled';
            } else {
                if (!$firstAvailableSlotChecked) {
                    $attribute = 'checked';
                    $firstAvailableSlotChecked = true;
                }
            }

            if (!$request->slots && !$request->selecteddate) {
                if ($firstAvailableSlotChecked === $timepartitionKey) {
                    $attribute = 'checked';
                }
            } else {
                $slots = explode(',', $request->slots);
                if (in_array($timepartition["start"]->format('H:i') . '-' . $timepartition["end"]->format('H:i'), $slots) && $request->selecteddate >= $currDate->format('Y-m-d')) {
                    $attribute = 'checked';
                }
            }
            $timeFormatValue = $timepartition["start"]->format('H:i') . '-' . $timepartition["end"]->format('H:i');
            if ($seatWiseBooking->time_format  == '24_hour') {
                $timeFormatLabel = $timepartition['start']->format('H:i') . ' to ' . $timepartition['end']->format('H:i');
            } else {
                $timeFormatLabel =  $timepartition['start']->format('h:i') . ' ' . $timepartition['start']->format('A') . ' to ' . $timepartition['end']->format('h:i') . ' ' . $timepartition['end']->format('A');
            }
            $inputType = 'radio';
            $inputName = 'session_time';
            $html .= '<input class="btn-check ' . $class . '" id="session_time_' . $timepartitionKey . '" name="' . $inputName . '" type="' . $inputType . '" value="' . $timeFormatValue . '" ' . $attribute . '>
            <label for="session_time_' . $timepartitionKey . '" class="my-2 btn btn-outline-primary">' . $timeFormatLabel . '</label>';
        }
        return response()->json(['is_success' => 1, 'html' => $html]);
    }

    public function getappoinmentSeatSeat(Request $request, $id)
    {
        $booking                = Booking::find($id);
        $seatWiseBooking        = SeatWiseBooking::where('booking_id', $booking->id)->first();
        $seatBookingJsons       = json_decode($seatWiseBooking->seat_booking_json, true);
        $bookingValues          = BookingValue::where('booking_id', $booking->id)->where('booking_seats_session', $request->session)->where('booking_seats_date', $request->date)->select('booking_seats', 'booking_seats_date', 'booking_seats_session')->get()->toArray();
        $inputType              = 'radio';
        $inputName              = 'seat';
        if ($seatWiseBooking->multiple_booking == 1) {
            $inputType          = 'checkbox';
            $inputName          = 'seat[]';
        }
        $seatBookinglimit        = 1;
        if ($seatWiseBooking->maximum_limit_status == 1) {
            $seatBookinglimit   = $seatWiseBooking->maximum_limit;
        }
        $html                   = '';
        foreach ($seatBookingJsons as $seatBookingJson) {
            $html .= '<tr><td>';
            for ($i = 1; $i <= $seatBookingJson['column']; $i++) {
                $attribute              = '';
                $class                  = '';
                if ($request->session) {
                    if (Self::isSeatBooked($bookingValues, $seatBookinglimit, $seatBookingJson['row'] . $i)) {
                        $attribute      = 'disabled';
                        $class          = 'disabled';
                    }
                    if ($request->selectedseat) {
                        $seats          = explode(',', $request->selectedseat);
                        if (in_array($seatBookingJson['row'] . $i, $seats)) {
                            $attribute  = 'checked';
                        }
                    }
                } else {
                    $attribute          = 'disabled';
                    $class              = 'disabled';
                }
                $html .= '<div class="seat">
                        <input class="btn-check ' . $class . '" ' . $attribute . '
                            id="seat_' . $seatBookingJson['row'] . $i . '"
                            type="' . $inputType . '" name="' . $inputName . '"
                            value="' . $seatBookingJson['row'] . $i . '">
                        <label for="seat_' . $seatBookingJson['row'] . $i . '"
                            class="btn btn-outline-primary">' . $seatBookingJson['row'] . $i . '</label>
                    </div>';
            }
            $html .= '</td></tr>';
        }
        return response()->json(['is_success' => 1, 'html' => $html]);
    }

    function isSeatBooked($bookingValues, $limitPerSlot, $seat)
    {
        $bookedCount = 0;
        foreach ($bookingValues as $bookingValue) {
            $bookedSeats = explode(',', $bookingValue['booking_seats']);
            if (in_array($seat, $bookedSeats)) {
                $bookedCount++;
                if ($bookedCount >= $limitPerSlot) {
                    return true;
                }
            }
        }
        return false;
    }

    public function bookingPaymentIntegration(Request $request, $id)
    {
        $booking                            = Booking::find($id);
        $paymentType                        = [];
        $paymentType['']                    = 'Select payment';
        if (UtilityFacades::getsettings('stripesetting') == 'on') {
            $paymentType['stripe']          = 'Stripe';
        }
        if (UtilityFacades::getsettings('paypalsetting') == 'on') {
            $paymentType['paypal']          = 'Paypal';
        }
        if (UtilityFacades::getsettings('razorpaysetting') == 'on') {
            $paymentType['razorpay']        = 'Razorpay';
        }
        if (UtilityFacades::getsettings('paytmsetting') == 'on') {
            $paymentType['paytm']           = 'Paytm';
        }
        if (UtilityFacades::getsettings('flutterwavesetting') == 'on') {
            $paymentType['flutterwave']     = 'Flutterwave';
        }
        if (UtilityFacades::getsettings('paystacksetting') == 'on') {
            $paymentType['paystack']        = 'Paystack';
        }
        if (UtilityFacades::getsettings('coingatesetting') == 'on') {
            $paymentType['coingate']        = 'Coingate';
        }
        if (UtilityFacades::getsettings('mercadosetting') == 'on') {
            $paymentType['mercado']         = 'Mercado';
        }
        return view('bookings.payment', compact('booking', 'paymentType'));
    }

    public function bookingPaymentIntegrationstore(Request $request, $id)
    {
        $booking = Booking::find($id);
        if ($request->payment_type == "paystack") {
            if ($request->currency_symbol != '₦' || $request->currency_name != 'NGN') {
                return redirect()->back()->with('failed', __('Currency not suppoted this payment getway. please enter NGN currency and ₦ symbol.'));
            }
        }
        if ($request->payment_type == "paytm") {
            if ($request->currency_symbol != '₹' || $request->currency_name != 'INR') {
                return redirect()->back()->with('failed', __('Currency not suppoted this payment getway. please enter INR currency and ₹ symbol.'));
            }
        }
        $booking->payment_status   = ($request->payment == 'on') ? '1' : '0';
        $booking->amount           = ($request->amount == '') ? '0' : $request->amount;
        $booking->currency_symbol  = $request->currency_symbol;
        $booking->currency_name    = $request->currency_name;
        $booking->payment_type     = $request->payment_type;
        $booking->save();
        return redirect()->route('bookings.index')->with('success', __('Form payment integration succesfully.'));
    }

    public function publicTimeFill($id)
    {
        $hashids                        = new Hashids('', 20);
        $id                             = $hashids->decodeHex($id);
        if ($id) {
            $booking = Booking::find($id);
            $timeWiseBooking            = TimeWiseBooking::where('booking_id', $booking->id)->first();
            if ($timeWiseBooking) {
                $timeZone               = $timeWiseBooking->time_zone;
                $currDate               = Carbon::now($timeZone);
                $bookingStartDate       = $currDate->format('Y-m-d');
                $bookingEndDate         = $currDate->copy()->endOfYear()->format('Y-m-d');
                if ($timeWiseBooking->limit_time_status == 1) {
                    $bookingStartDate   = $timeWiseBooking->start_date;
                    $bookingEndDate     = $timeWiseBooking->end_date;
                }
                if ($timeWiseBooking->rolling_days_status == 1) {
                    $bookingStartDate   = $currDate->format('Y-m-d');
                    $bookingEndDate     = $currDate->copy()->addDays($timeWiseBooking->rolling_days)->format('Y-m-d');
                }
                $dayMapping = [
                    'sunday'    => 0,
                    'monday'    => 1,
                    'tuesday'   => 2,
                    'wednesday' => 3,
                    'thursday'  => 4,
                    'friday'    => 5,
                    'saturday'  => 6,
                ];
                $daysArray              = explode(',', $timeWiseBooking->week_time);
                $selectedDays           = array_map(function ($day) use ($dayMapping) {
                    return $dayMapping[strtolower(trim($day))];
                }, $daysArray);
                $bookingValue           = null;
                if ($booking) {
                    $array              = $booking->getFormArray();
                    return view('bookings.public-fill.time', compact('booking', 'bookingValue', 'array', 'timeWiseBooking', 'bookingStartDate', 'bookingEndDate', 'selectedDays', 'currDate'));
                } else {
                    return redirect()->back()->with('failed', __('Form not found.'));
                }
            } else {
                return redirect()->back()->with('errors', __('Please complete your booking proccess.'));
            }
        } else {
            abort(404);
        }
    }

    public function publicSeatFill($id)
    {
        $hashids                            = new Hashids('', 20);
        $id                                 = $hashids->decodeHex($id);
        if ($id) {
            $booking                        = Booking::find($id);
            $seatWiseBooking                = SeatWiseBooking::where('booking_id', $booking->id)->first();
            if ($seatWiseBooking) {
                $array                      = json_decode($booking->json);
                $timeZone                   = $seatWiseBooking->time_zone;
                $currDate                   = Carbon::now($timeZone);
                $bookingStartDate           = $currDate->format('Y-m-d');
                $bookingEndDate             = $currDate->copy()->endOfYear()->format('Y-m-d');
                if ($seatWiseBooking->limit_time_status == 1) {
                    $bookingStartDate       = $seatWiseBooking->start_date;
                    $bookingEndDate         = $seatWiseBooking->end_date;
                }
                if ($seatWiseBooking->rolling_days_status == 1) {
                    $bookingStartDate       = $currDate->format('Y-m-d');
                    $bookingEndDate         = $currDate->copy()->addDays($seatWiseBooking->rolling_days)->format('Y-m-d');
                }
                $dayMapping = [
                    'sunday' => 0,
                    'monday' => 1,
                    'tuesday' => 2,
                    'wednesday' => 3,
                    'thursday' => 4,
                    'friday' => 5,
                    'saturday' => 6,
                ];
                $daysArray                  = explode(',', $seatWiseBooking->week_time);
                $selectedDays = array_map(function ($day) use ($dayMapping) {
                    return $dayMapping[strtolower(trim($day))];
                }, $daysArray);
                $bookingValue               = null;
                if ($booking) {
                    $array                  = $booking->getFormArray();
                    return view('bookings.public-fill.seats', compact('booking', 'bookingValue', 'array', 'seatWiseBooking', 'selectedDays', 'bookingStartDate', 'bookingEndDate', 'currDate'));
                } else {
                    return redirect()->back()->with('failed', __('Form not found.'));
                }
            } else {
                return redirect()->back()->with('errors', __('Please complete your booking proccess.'));
            }
        } else {
            abort(404);
        }
    }

    public function qrCode($id)
    {
        $hashids                = new Hashids('', 20);
        $id                     = $hashids->decodeHex($id);
        $booking                = Booking::find($id);
        $view                   = view('bookings.public-fill-qr', compact('booking'));
        return ['html' => $view->render()];
    }

    public function fillStore(Request $request, $id)
    {
        $booking = Booking::find($id);
        if (UtilityFacades::getsettings('captcha_enable') == 'on') {
            if (UtilityFacades::keysettings('captcha') == 'hcaptcha') {
                if (empty($_POST['h-captcha-response'])) {
                    if (isset($request->ajax)) {
                        return response()->json(['is_success' => false, 'message' => __('Please check hcaptcha.')], 200);
                    } else {
                        return redirect()->back()->with('failed', __('Please check hcaptcha.'));
                    }
                }
            }
            if (UtilityFacades::keysettings('captcha') == 'recaptcha') {
                if (empty($_POST['g-recaptcha-response'])) {
                    if (isset($request->ajax)) {
                        return response()->json(['is_success' => false, 'message' => __('Please check reCAPTCHA.')], 200);
                    } else {
                        return redirect()->back()->with('failed', __('Please check reCAPTCHA.'));
                    }
                }
            }
        }
        if ($booking) {
            $clientEmails = [];
            if ($request->booking_value_id) {
                $bookingValue = BookingValue::find($request->booking_value_id);
                $array        = json_decode($bookingValue->json);
            } else {
                $array        = $booking->getFormArray();
            }
            $route = route('appointment.edit', $id);
            $validator = [];
            if ($booking->booking_slots == 'time_wise_booking' && $request->booking_date && $request->slot) {
                $timeWiseBooking = TimeWiseBooking::where('booking_id', $booking->id)->first();
                $input          = $request->all();
                $input['slot'] = $selectedSlots = Arr::wrap($request->slot);
                Validator::extend('limit_slots', function ($attribute, $value, $parameters, $validator) use ($timeWiseBooking) {
                    return is_array($value) && count($value) <= $timeWiseBooking->multiple_booking_limit;
                }, "The number of selected time slots exceeds the allowed limit of {$timeWiseBooking->multiple_booking_limit}.");
                $validator = Validator::make($input, [
                    'slot' => 'required|limit_slots',
                ], [
                    'limit_slots' => "The number of selected time slots exceeds the allowed limit of {$timeWiseBooking->multiple_booking_limit}.",
                ]);
                $bookingValues = BookingValue::where('booking_id', $booking->id)
                    ->where('booking_slots_date', $request->booking_date)
                    ->select('booking_slots_date', 'booking_slots')
                    ->get()
                    ->toArray();
                $conflictingSlots = [];
                foreach ($selectedSlots as $selectedSlot) {
                    if (Self::isSlotBooked($bookingValues, $timeWiseBooking->multiple_booking_limit, $selectedSlot)) {
                        $conflictingSlots[] = $selectedSlot;
                    }
                }

                if (!empty($conflictingSlots)) {
                    $formattedConflictingSlots = [];
                    foreach ($conflictingSlots as $slot) {
                        $times                       = explode('-', $slot);
                        $startTime                   = \DateTime::createFromFormat('H:i', $times[0]);
                        $formattedStartTime          = $startTime->format('h:i A');
                        $endTime                     = \DateTime::createFromFormat('H:i', $times[1]);
                        $formattedEndTime            = $endTime->format('h:i A');
                        $formattedConflictingSlots[] = $formattedStartTime . ' to ' . $formattedEndTime;
                    }

                    $errorMessage = 'One or more selected slots are already booked. Already booked slots: ' . implode(', ', $formattedConflictingSlots);
                    return $request->ajax() ? response()->json(['is_success' => false, 'message' => $errorMessage], 200) : redirect()->back()->with('failed', $errorMessage);
                }
            } else if ($booking->booking_slots == 'seats_wise_booking' && $request->booking_date && $request->session_time && $request->seat) {
                $seatWiseBooking = SeatWiseBooking::where('booking_id', $booking->id)->first();
                $input          = $request->all();
                $input['seat'] = $selectedSeats = Arr::wrap($request->seat);
                Validator::extend('limit_seats', function ($attribute, $value, $parameters, $validator) use ($seatWiseBooking) {
                    return is_array($value) && count($value) <= $seatWiseBooking->multiple_booking_limit;
                }, "The number of selected seats exceeds the allowed limit of {$seatWiseBooking->multiple_booking_limit}.");
                $validator = Validator::make($input, [
                    'seat' => 'required|limit_seats',
                ], [
                    'limit_seats' => "The number of selected seats exceeds the allowed limit of {$seatWiseBooking->multiple_booking_limit}.",
                ]);

                $bookingValues = BookingValue::where('booking_id', $booking->id)
                    ->where('booking_seats_session', $request->session_time)
                    ->where('booking_seats_date', $request->booking_date)
                    ->pluck('booking_seats')
                    ->toArray();
                $seatCounts = [];
                foreach ($bookingValues as $bookingValue) {
                    $bookedSeats = explode(',', $bookingValue);
                    foreach ($bookedSeats as $seat) {
                        if (!isset($seatCounts[$seat])) {
                            $seatCounts[$seat] = 1;
                        } else {
                            $seatCounts[$seat]++;
                        }
                    }
                }
                foreach ($selectedSeats as $selectedSeat) {
                    if (isset($seatCounts[$selectedSeat]) && $seatCounts[$selectedSeat] >= $seatWiseBooking->multiple_booking_limit) {
                        $message = "One or more selected seats are already booked. Already booked seat is : '{$selectedSeat}'.";
                        return $request->ajax() ? response()->json(['is_success' => false, 'message' => $message], 200) : redirect()->back()->with('failed', $message);
                    }
                }
            }
            if ($validator && $validator->fails()) {
                return $request->ajax() ? response()->json(['is_success' => false, 'message' => $validator->errors()->first()], 200) : redirect()->back()->with('failed', $validator->errors()->first());
            }
            foreach ($array as  &$rows) {
                foreach ($rows as &$row) {
                    if ($row->type == 'checkbox-group') {
                        foreach ($row->values as &$checkboxvalue) {
                            if (is_array($request->{$row->name}) && in_array($checkboxvalue->value, $request->{$row->name})) {
                                $checkboxvalue->selected = 1;
                            } else {
                                if (isset($checkboxvalue->selected)) {
                                    unset($checkboxvalue->selected);
                                }
                            }
                        }
                    } elseif ($row->type == 'file') {
                        if ($row->subtype == "fineuploader") {
                            $file_size = number_format($row->max_file_size_mb / 1073742848, 2);
                            $file_limit = $row->max_file_size_mb / 1024;
                            if ($file_size < $file_limit) {
                                $values = [];
                                $value = explode(',', $request->input($row->name));
                                foreach ($value as $file) {
                                    $values[] = $file;
                                }
                                $row->value = $values;
                            } else {
                                return response()->json(['is_success' => false, 'message' => __("Please upload maximum $row->max_file_size_mb MB file size.")], 200);
                            }
                        } else {
                            if ($row->file_extention == 'pdf') {
                                $allowedFileExtension = ['pdf', 'pdfa', 'fdf', 'xdp', 'xfa', 'pdx', 'pdp', 'pdfxml', 'pdxox'];
                            } else if ($row->file_extention == 'image') {
                                $allowedFileExtension = ['jpeg', 'jpg', 'png'];
                            } else if ($row->file_extention == 'excel') {
                                $allowedFileExtension = ['xlsx', 'csv', 'xlsm', 'xltx', 'xlsb', 'xltm', 'xlw'];
                            }
                            $requiredExtension  = implode(',', $allowedFileExtension);
                            $fileSizeLimit      = $row->max_file_size_mb * 1024;

                            if ($request->hasFile($row->name)) {
                                if ($row->multiple) {
                                    $uploadValues   = [];
                                    $files          = $request->file($row->name);
                                    foreach ($files as $file) {
                                        $extension  = $file->getClientOriginalExtension();
                                        $fileSize   = $file->getSize() / 1024;
                                        if (!in_array($extension, $allowedFileExtension) || $fileSize >= $fileSizeLimit) {
                                            $errorMessage = __("Invalid file type or size. Please upload files with extensions: $requiredExtension and maximum size $row->max_file_size_mb MB.");
                                            return $request->ajax ? response()->json(['is_success' => false, 'message' => $errorMessage], 200) : redirect()->back()->with('error', $errorMessage);
                                        }
                                        if ($extension == 'csv') {
                                            $name     = \Str::random(40) . '.' . $extension;
                                            $file->move(storage_path() . '/app/booking-values/' . $booking->id, $name);
                                            $uploadValues[] = 'booking-values/' . $booking->id . '/' . $name;
                                        } else {
                                            $path      = Storage::path("booking-values/$booking->id");
                                            $fileName  = $file->store('booking-values/' . $booking->id);
                                            if (!file_exists($path)) {
                                                mkdir($path, 0777, true);
                                                chmod($path, 0777);
                                            }
                                            if (!file_exists(Storage::path($fileName))) {
                                                mkdir(Storage::path($fileName), 0777, true);
                                                chmod(Storage::path($fileName), 0777);
                                            }
                                            $uploadValues[] = $fileName;
                                        }
                                    }
                                    $row->value = $uploadValues;
                                } else {
                                    $file       = $request->file($row->name);
                                    $extension  = $file->getClientOriginalExtension();
                                    $fileSize   = $file->getSize() / 1024;
                                    if (!in_array($extension, $allowedFileExtension) || $fileSize >= $fileSizeLimit) {
                                        $errorMessage = __("Invalid file type or size. Please upload files with extensions: $requiredExtension and maximum size $row->max_file_size_mb MB.");
                                        return $request->ajax ? response()->json(['is_success' => false, 'message' => $errorMessage], 200) : redirect()->back()->with('failed', $errorMessage);
                                    }
                                    if ($extension == 'csv') {
                                        $name   = \Str::random(40) . '.' . $extension;
                                        $file->move(storage_path() . '/app/booking-values/' . $booking->id, $name);
                                        $values = 'booking-values/' . $booking->id . '/' . $name;
                                        chmod("$values", 0777);
                                    } else {
                                        $path      = Storage::path("booking-values/$booking->id");
                                        $fileName  = $file->store('booking-values/' . $booking->id);
                                        if (!file_exists($path)) {
                                            mkdir($path, 0777, true);
                                            chmod($path, 0777);
                                        }
                                        if (!file_exists(Storage::path($fileName))) {
                                            mkdir(Storage::path($fileName), 0777, true);
                                            chmod(Storage::path($fileName), 0777);
                                        }
                                        $values = $fileName;
                                    }
                                    $row->value = $values;
                                }
                            }
                        }
                    } elseif ($row->type == 'radio-group') {
                        foreach ($row->values as &$radiovalue) {
                            if ($radiovalue->value == $request->{$row->name}) {
                                $radiovalue->selected = 1;
                            } else {
                                if (isset($radiovalue->selected)) {
                                    unset($radiovalue->selected);
                                }
                            }
                        }
                    } elseif ($row->type == 'autocomplete') {
                        if (isset($row->multiple)) {
                            foreach ($row->values as &$autocompletevalue) {
                                if (is_array($request->{$row->name}) && in_array($autocompletevalue->value, $request->{$row->name})) {
                                    $autocompletevalue->selected = 1;
                                } else {
                                    if (isset($autocompletevalue->selected)) {
                                        unset($autocompletevalue->selected);
                                    }
                                }
                            }
                        } else {
                            foreach ($row->values as &$autocompletevalue) {
                                if ($autocompletevalue->value == $request->autocomplete) {
                                    $autocompletevalue->selected = 1;
                                } else {
                                    if (isset($autocompletevalue->selected)) {
                                        unset($autocompletevalue->selected);
                                    }
                                    $row->value = $request->autocomplete;
                                }
                            }
                        }
                    } elseif ($row->type == 'select') {
                        if ($row->multiple) {
                            foreach ($row->values as &$selectvalue) {
                                if (is_array($request->{$row->name}) && in_array($selectvalue->value, $request->{$row->name})) {
                                    $selectvalue->selected = 1;
                                } else {
                                    if (isset($selectvalue->selected)) {
                                        unset($selectvalue->selected);
                                    }
                                }
                            }
                        } else {
                            foreach ($row->values as &$selectvalue) {
                                if ($selectvalue->value == $request->{$row->name}) {
                                    $selectvalue->selected = 1;
                                } else {
                                    if (isset($selectvalue->selected)) {
                                        unset($selectvalue->selected);
                                    }
                                }
                            }
                        }
                    } elseif ($row->type == 'date' || $row->type == 'number' || $row->type == 'textarea' || $row->type == 'spinner') {
                        $row->value = $request->{$row->name};
                    } elseif ($row->type == 'text') {
                        $clientEmail = '';
                        if ($row->subtype == 'email') {
                            if (isset($row->is_client_email) && $row->is_client_email) {
                                $emailValue = $request->{$row->name};
                                if (isset($emailValue) && !empty($emailValue) && !filter_var($emailValue, FILTER_VALIDATE_EMAIL)) {
                                    if (isset($request->ajax)) {
                                        return response()->json(['is_success' => false, 'message' => __('Please enter a valid email address.')], 200);
                                    } else {
                                        return redirect()->back()->with('failed', __('Please enter a valid email address.'));
                                    }
                                } else {
                                    $clientEmails[] = $emailValue;
                                }
                            }
                        }
                        $row->value = $request->{$row->name};
                    } elseif ($row->type == 'starRating') {
                        $row->value = $request->{$row->name};
                    } elseif ($row->type == 'SignaturePad') {
                        if (property_exists($row, 'value')) {
                            $filepath = $row->value;
                            if ($request->{$row->name} == null) {
                                $url = $row->value;
                            } else {
                                if (file_exists(Storage::path($request->{$row->name}))) {
                                    $url = $request->{$row->name};
                                    $path = $url;
                                    $imgUrl = Storage::path($url);
                                    $filePath = $imgUrl;
                                } else {
                                    $imgUrl = $request->{$row->name};
                                    $path = "booking-values/$booking->id/" . rand(1, 1000) . '.png';
                                    $filePath = Storage::path($path);
                                }
                                $imageContent = file_get_contents($imgUrl);
                                $file = file_put_contents($filePath, $imageContent);
                            }
                            $row->value = $path;
                        } else {
                            if ($request->{$row->name} != null) {
                                if (!file_exists(Storage::path("booking-values/$booking->id"))) {
                                    mkdir(Storage::path("booking-values/$booking->id"), 0777, true);
                                    chmod(Storage::path("booking-values/$booking->id"), 0777);
                                }
                                $filepath     = "booking-values/$booking->id/" . rand(1, 1000) . '.png';
                                $url          = $request->{$row->name};
                                $imageContent = file_get_contents($url);
                                $filePath     = Storage::path($filepath);
                                $file         = file_put_contents($filePath, $imageContent);
                                $row->value   = $filepath;
                            }
                        }
                    } elseif ($row->type == 'location') {
                        $row->value = $request->location;
                    } elseif ($row->type == 'video') {
                        request()->validate([
                            'media' => 'required|mimes:webm,mp4',
                        ]);
                        $row->value = $request->media;
                    } elseif ($row->type == 'selfie') {
                        $file = '';
                        $img = $request->image;
                        $folderPath = "booking-selfie/";
                        $imageParts = explode(";base64,", $img);
                        if ($imageParts[0]) {
                            $imageBase64 = base64_decode($imageParts[1]);
                            $fileName = uniqid() . '.png';
                            $file = $folderPath . $fileName;
                            Storage::put($file, $imageBase64);
                        }
                        $row->value  = $file;
                    }
                }
            }

            if ($request->booking_value_id) {
                $bookingValue->json = json_encode($array);
                $bookingValue->save();
            } else {
                if (Auth::user()) {
                    $UserID = Auth::user()->id;
                } else {
                    $UserID = NULL;
                }
                $currDate = Carbon::now();
                $data = [];
                if ($booking->payment_status == 1) {
                    if ($booking->payment_type == 'stripe') {
                        StripeStripe::setApiKey(UtilityFacades::getsettings('stripe_secret', $booking->created_by));
                        try {
                            $charge = \Stripe\PaymentIntent::create([
                                'description' => "Payment from " . config('app.name'),
                                'amount' => $booking->amount * 100,
                                'currency' => $booking->currency_name,
                                'payment_method_types' => ['card'],
                                'metadata' => [
                                    'order_id' => '12345',
                                ],
                            ]);
                        } catch (Exception $e) {
                            return response()->json(['status' => false, 'message' => $e->getMessage()], 200);
                        }
                        if ($charge) {
                            $data['transaction_id']  = $charge->id;
                            $data['currency_symbol'] = $booking->currency_symbol;
                            $data['currency_name']   = $booking->currency_name;
                            $data['amount']          = $booking->amount;
                            $data['status']          = 'successfull';
                            $data['payment_type']    = 'Stripe';
                        }
                    } else if ($booking->payment_type == 'razorpay') {
                        $data['transaction_id']  = $request->payment_id;
                        $data['currency_symbol'] = $booking->currency_symbol;
                        $data['currency_name']   = $booking->currency_name;
                        $data['amount']          = $booking->amount;
                        $data['status']          = 'successfull';
                        $data['payment_type']    = 'Razorpay';
                    } else if ($booking->payment_type == 'paypal') {
                        $data['transaction_id']  = $request->payment_id;
                        $data['currency_symbol'] = $booking->currency_symbol;
                        $data['currency_name']   = $booking->currency_name;
                        $data['amount']          = $booking->amount;
                        $data['status']          = 'successfull';
                        $data['payment_type']    = 'Paypal';
                    } else if ($booking->payment_type == 'flutterwave') {
                        $data['transaction_id']  = $request->payment_id;
                        $data['currency_symbol'] = $booking->currency_symbol;
                        $data['currency_name']   = $booking->currency_name;
                        $data['amount']          = $booking->amount;
                        $data['status']          = 'successfull';
                        $data['payment_type'] = 'Flutterwave';
                    } else if ($booking->payment_type == 'paytm') {
                        $data['transaction_id']  = $request->payment_id;
                        $data['currency_symbol'] = $booking->currency_symbol;
                        $data['currency_name']   = $booking->currency_name;
                        $data['amount']          = $booking->amount;
                        $data['status']          = 'pending';
                        $data['payment_type']    = 'Paytm';
                    } else if ($booking->payment_type == 'paystack') {
                        $data['transaction_id']   = $request->payment_id;
                        $data['currency_symbol']  = $booking->currency_symbol;
                        $data['currency_name']    = $booking->currency_name;
                        $data['amount']           = $booking->amount;
                        $data['status']           = 'successfull';
                        $data['payment_type'] = 'Paystack';
                    } else if ($booking->payment_type == 'coingate') {
                        $data['status'] = 'pending';
                    } else if ($booking->payment_type == 'mercado') {
                        $data['status'] = 'pending';
                    }
                } else {
                    $data['status'] = 'free';
                }
                $data['booking_id']             = $booking->id;
                $data['user_id']                = $UserID;
                $data['booking_slots_date']     = ($request->booking_date) ? $request->booking_date : null;
                $data['booking_slots']          = ($request->slot) ? (is_array($request->slot) ? implode(',', $request->slot) : $request->slot) : null;
                $data['booking_seats_date']     = ($request->booking_date) ? $request->booking_date : $currDate->format('Y-m-d');
                $data['booking_seats_session']  = ($request->session_time) ? $request->session_time : null;
                $data['booking_seats']          = ($request->seat) ? (is_array($request->seat) ? implode(',', $request->seat) : $request->seat) : null;
                $data['json']                   = json_encode($array);
                $bookingValue                   = BookingValue::create($data);
            }
            $bookingValueArray                  = json_decode($bookingValue->json);
            $emails                             = explode(',', $booking->business_email);

            if (UtilityFacades::getsettings('email_setting_enable') == 'on') {
                if ($emails) {
                    try {
                        Mail::to($emails)->send(new BookingSubmitEmail($bookingValue, $bookingValueArray));
                    } catch (\Exception $e) {
                        if (isset($request->ajax)) {
                            return response()->json(['is_success' => true, 'message' => $e->getMessage(), 'redirect' => $route], 200);
                        } else {
                            return redirect()->back()->with('success', $e->getMessage());
                        }
                    }
                }
                foreach ($clientEmails as $clientEmail) {
                    try {
                        Mail::to($clientEmail)->send(new BookingThanksmail($bookingValue));
                    } catch (\Exception $e) {
                        if (isset($request->ajax)) {
                            return response()->json(['is_success' => true, 'message' => $e->getMessage(), 'redirect' => $route], 200);
                        } else {
                            return redirect()->back()->with('success', $e->getMessage());
                        }
                    }
                }
            }
            $user                 = User::where('type', 'Admin')->first();
            $notificationsSetting = NotificationsSetting::where('title', 'new booking survey details')->first();
            if (isset($notificationsSetting)) {
                if ($notificationsSetting->notify == '1') {
                    $user->notify(new NewBookingSurveyDetails($booking));
                }
                if ($notificationsSetting->email_notification == '1') {
                    if (UtilityFacades::getsettings('email_setting_enable') == 'on') {
                        if (MailTemplate::where('mailable', BookingSubmitEmail::class)->first()) {
                            try {
                                Mail::to($bookingValue->email)->send(new BookingSubmitEmail($bookingValue, $bookingValueArray));
                            } catch (\Exception $e) {
                                if (isset($request->ajax)) {
                                    return response()->json(['is_success' => true, 'message' => $e->getMessage(), 'redirect' => $route], 200);
                                } else {
                                    return redirect()->back()->with('success', $e->getMessage());
                                }
                            }
                        }
                    }
                }
            }
            if ($booking->payment_type != 'coingate' && $booking->payment_type != 'mercado') {
                $successMsg = strip_tags('Thank You');
            }
            if ($request->booking_value_id) {
                $successMsg = strip_tags('Thank You');
            }
            $hashids    = new Hashids('', 20);
            $id         = $hashids->encodeHex($bookingValue->id);
            $route      = route('appointment.edit', $id);
            if (isset($request->ajax)) {
                return response()->json(['is_success' => true, 'message' => $successMsg, 'redirect' => $route], 200);
            } else {
                return redirect()->back()->with('success', $successMsg);
            }
        } else {
            if (isset($request->ajax)) {
                return response()->json(['is_success' => false, 'message' => __('Form not found')], 200);
            } else {
                return redirect()->back()->with('failed', __('Form not found.'));
            }
        }
    }

    public function bookingCalendar()
    {
        $arrayJson          = [];
        $bookingValues      = BookingValue::join('bookings', 'bookings.id', 'booking_values.booking_id')->select('booking_values.*')->get();
        foreach ($bookingValues as $bookingValue) {
            if ($bookingValue->booking_seats_date || $bookingValue->booking_slots_date) {
                $appointMents          = explode(',', $bookingValue->booking_slots);
                if ($bookingValue->Booking->booking_slots == 'seats_wise_booking') {
                    $appointMents      = explode(',', $bookingValue->booking_seats);
                }
                foreach ($appointMents as $appointMent) {
                    if ($bookingValue->Booking->booking_slots == 'seats_wise_booking') {
                        $url         = route('seats.bookingvalues.show', $bookingValue->id);
                        $date        = Carbon::parse($bookingValue->booking_seats_date);
                        $startDate   = $bookingValue->booking_seats_date;
                    } else {
                        $url         = route('timing.bookingvalues.show', $bookingValue->id);
                        $date        = Carbon::parse($bookingValue->booking_slots_date);
                        $startDate   = $bookingValue->booking_slots_date;
                    }
                    $arrayJson[] = [
                        "id"         => $bookingValue->id,
                        "title"      => $bookingValue->Booking->business_name . ' ' . $appointMent,
                        "start"      => $startDate,
                        "end"        => $date->format('Y-m-d H:i:s'),
                        "className"  => 'event-primary',
                        'url'        => $url,
                        "allDay"     => true,
                    ];
                }
            }
        }
        $bookingData = json_encode($arrayJson);
        return view('bookings.calender', compact('bookingData'));
    }
}
