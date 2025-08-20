<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\FooterSetting;
use App\Models\PageSetting;
use App\Models\settings;
use App\Models\Testimonial;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LandingPageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $settings = [
            ['key' => 'apps_setting_enable', 'value' => 'on'],
            ['key' => 'apps_name', 'value' => 'Nombre del Sistema'],
            ['key' => 'apps_bold_name', 'value' => 'Form Builder'],
            ['key' => 'app_detail', 'value' => ''],
            ['key' => 'apps_image', 'value' => 'seeder-images/app.png'],
            ['key' => 'apps_multiple_image_setting', 'value' => '[{"apps_multiple_image":"seeder-images/1.png"},
                                                                  {"apps_multiple_image":"seeder-images/2.png"},
                                                                  {"apps_multiple_image":"seeder-images/3.png"},
                                                                  {"apps_multiple_image":"seeder-images/4.png"},
                                                                  {"apps_multiple_image":"seeder-images/5.png"},
                                                                  {"apps_multiple_image":"seeder-images/6.png"},
                                                                  {"apps_multiple_image":"seeder-images/7.png"},
                                                                  {"apps_multiple_image":"seeder-images/8.png"},
                                                                  {"apps_multiple_image":"seeder-images/9.png"}]'],


            ['key' => 'feature_setting_enable', 'value' => 'on'],
            ['key' => 'feature_name', 'value' => 'Nombre del Sistema'],
            ['key' => 'feature_bold_name', 'value' => 'Features'],
            ['key' => 'feature_detail', 'value' => ''],
            ['key' => 'feature_setting', 'value' => '[
                                                        {"feature_image":"seeder-images/active.svg","feature_name":"Email Notification","feature_bold_name":"On From Submit","feature_detail":"You can send a notification email to someone in your organization when a contact submits a form. You can use this type of form processing step so that..."},
                                                        {"feature_image":"seeder-images/security.svg","feature_name":"Two Factor","feature_bold_name":"Authentication","feature_detail":"Security is our priority. With our robust two-factor authentication (2FA) feature, you can add an extra layer of protection to your Prime Laravel Form"},
                                                        {"feature_image":"seeder-images/secretary.svg","feature_name":"Multi Users With","feature_bold_name":"Role & permission","feature_detail":"Assign roles and permissions to different users based on their responsibilities and requirements. Admins can manage user accounts, define access level"},
                                                        {"feature_image":"seeder-images/documents.svg","feature_name":"Document builder","feature_bold_name":"Easy and fast","feature_detail":"Template Library: Offer a selection of pre-designed templates for various document types (e.g., contracts, reports).Template Creation: Allow users to create custom templates with placeholders for dynamic content.\r\n\r\nTemplate Library: Offer a selection of pre-designed templates for various document types (e.g., contracts, reports).Template Creation: Allow users to create custom templates with placeholders for dynamic content."}]'],


            ['key' => 'menu_setting_section1_enable', 'value' => 'on'],
            ['key' => 'menu_image_section1', 'value' => 'seeder-images/menusection1.png'],
            ['key' => 'menu_name_section1', 'value' => 'Form Builder'],
            ['key' => 'menu_bold_name_section1', 'value' => 'With Drag & Drop Dashboard Widgets'],
            ['key' => 'menu_detail_section1', 'value' => 'Creating beautiful dashboards has never been easier. Our drag-and-drop interface lets you effortlessly arrange and resize widgets, allowing you to design dynamic and interactive dashboards without any coding.'],

            ['key' => 'menu_setting_section2_enable', 'value' => 'on'],
            ['key' => 'menu_image_section2', 'value' => 'seeder-images/menusection12.png'],
            ['key' => 'menu_name_section2', 'value' => 'Multi builders'],
            ['key' => 'menu_bold_name_section2', 'value' => 'Poll Management & Document Generator & Booking System'],
            ['key' => 'menu_detail_section2', 'value' => 'you can create customized surveys with ease. From multiple choice questionss to rating scales, our drag-and-drop builder lets you construct your polls in minutes, saving you valuable time and effort.'],

            ['key' => 'menu_setting_section3_enable', 'value' => 'on'],
            ['key' => 'menu_image_section3', 'value' => 'seeder-images/setting.png'],
            ['key' => 'menu_name_section3', 'value' => 'Setting Features With'],
            ['key' => 'menu_bold_name_section3', 'value' => 'Multiple Section settings'],
            ['key' => 'menu_detail_section3', 'value' => 'A settings page is a crucial component of many digital products, allowing users to customize their experience according to their preferences. Designing a settings page with dynamic data enhances user satisfaction and engagement. Here s a guide on how to create such a page.'],

            ['key' => 'business_growth_setting_enable', 'value' => 'on'],
            ['key' => 'business_growth_front_image', 'value' => 'seeder-images/10.png'],
            ['key' => 'business_growth_video', 'value' => 'seeder-images/Dashboard _ Nombre del Sistema.mp4'],
            ['key' => 'business_growth_name', 'value' => 'Makes Quick'],
            ['key' => 'business_growth_bold_name', 'value' => 'Business Growth'],
            ['key' => 'business_growth_detail', 'value' => 'Offer unique products, services, or solutions that stand out in the market. Innovation and differentiation can attract customers and give you a competitive edge.'],
            ['key' => 'business_growth_view_setting', 'value' => '[{"business_growth_view_name":"Positive Reviews","business_growth_view_amount":"20 k+"},{"business_growth_view_name":"Total Sales","business_growth_view_amount":"300 +"},{"business_growth_view_name":"Happy Users","business_growth_view_amount":"100 k+"}]'],
            ['key' => 'business_growth_setting', 'value' => '[{"business_growth_title":"User Friendly"},{"business_growth_title":"Products Analytic"},{"business_growth_title":"Manufacturers"},{"business_growth_title":"Order Status Tracking"},{"business_growth_title":"Supply Chain"},{"business_growth_title":"Chatting Features"},{"business_growth_title":"Workflows"},{"business_growth_title":"Transformation"},{"business_growth_title":"Easy Payout"},{"business_growth_title":"Data Adjustment"},{"business_growth_title":"Order Status Tracking"},{"business_growth_title":"Store Swap Link"},{"business_growth_title":"Manufacturers"},{"business_growth_title":"Order Status Tracking"}]'],


            ['key' => 'form_setting_enable', 'value' => 'on'],
            ['key' => 'form_name', 'value' => 'Survey Form'],
            ['key' => 'form_detail', 'value' => ''],

            ['key' => 'faq_setting_enable', 'value' => 'on'],
            ['key' => 'faq_name', 'value' => 'Frequently asked questionss'],


            ['key' => 'blog_setting_enable', 'value' => 'on'],
            ['key' => 'blog_name', 'value' => 'BLOGS'],
            ['key' => 'blog_detail', 'value' => ''],


            ['key' => 'start_view_setting_enable', 'value' => 'on'],
            ['key' => 'start_view_name', 'value' => 'Nombre del Sistema'],
            ['key' => 'start_view_detail', 'value' => ''],
            ['key' => 'start_view_link_name', 'value' => 'Contact US'],
            ['key' => 'start_view_link', 'value' => '#'],
            ['key' => 'start_view_image', 'value' => 'seeder-images//11.png'],

            ['key' => 'footer_setting_enable', 'value' => 'on'],
            ['key' => 'footer_description', 'value' => ''],

        ];

        foreach ($settings as $setting) {
            settings::firstOrCreate($setting);
        }

       



        $PageSetting1 = PageSetting::firstOrCreate(
            [
                'title' => 'About Us',
            ],
            [
                'type' => 'desc',
                'url_type' => null,
                'page_url' => null,
                'friendly_url' => null,
                'description' => '',
            ]
        );



        $PageSetting2 = PageSetting::firstOrCreate(
            [
                'title' => 'Our Team',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );

      

        $PageSetting3 = PageSetting::firstOrCreate(
            [
                'title' => 'Products',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '',
                'friendly_url' => '',
                'description' => null,
            ]
        );

       


        $PageSetting4 = PageSetting::firstOrCreate(
            [
                'title' => 'Contact',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => url('contact/us'),
                'friendly_url' => url('contact/us'),
                'description' => null,
            ]
        );

       
        $PageSetting5 =  PageSetting::firstOrCreate(
            [
                'title' => 'Feature',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );

       

        $PageSetting6 =  PageSetting::firstOrCreate(
            [
                'title' => 'Pricing',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );

       


        $PageSetting7 = PageSetting::firstOrCreate(
            [
                'title' => 'Credit',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );

      


        $PageSetting8 = PageSetting::firstOrCreate(
            [
                'title' => 'News',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );

       


        $PageSetting9 =  PageSetting::firstOrCreate(
            [
                'title' => 'iOS',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );



        $PageSetting10 = PageSetting::firstOrCreate(
            [
                'title' => 'Android',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );

       
        $PageSetting11 =  PageSetting::firstOrCreate(
            [
                'title' => 'Microsoft',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );


        $PageSetting12 =   PageSetting::firstOrCreate(
            [
                'title' => 'Desktop',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );


        $PageSetting13 =  PageSetting::firstOrCreate(
            [
                'title' => 'Help',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => '#',
                'friendly_url' => '#',
                'description' => null,
            ]
        );

        $PageSetting14 =   PageSetting::firstOrCreate(
            [
                'title' => 'Terms',
            ],
            [
                'type' => 'desc',
                'url_type' => null,
                'page_url' => null,
                'friendly_url' => null,
                'description' => '',
            ]
        );

      

        $PageSetting15 =   PageSetting::firstOrCreate(
            [
                'title' => 'FAQ',
            ],
            [
                'type' => 'link',
                'url_type' => 'internal link',
                'page_url' => url('all/faqs'),
                'friendly_url' => url('all/faqs'),
                'description' => null,
            ]
        );



        $PageSetting16 =    PageSetting::firstOrCreate(
            [
                'title' => 'Privacy',
            ],
            [
                'type' => 'desc',
                'url_type' => null,
                'page_url' => null,
                'friendly_url' => null,
                'description' => '',
            ]
        );

    }
}
