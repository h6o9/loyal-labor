<?php

namespace Modules\PageBuilder\database\seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\PageBuilder\app\Models\CustomizablePageTranslation;
use Modules\PageBuilder\app\Models\CustomizeablePage;

class PageBuilderDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Model::unguard();

        $page       = new CustomizeablePage;
        $page->slug = 'terms-contidions';
        if ($page->save()) {
            foreach (allLanguages() as $language) {
                CustomizablePageTranslation::create([
                    'customizeable_page_id' => $page->id,
                    'lang_code'             => $language->code,
                    'title'                 => 'Terms & Conditions',
                    'description'           => '<h3 class="title">Who we are</h3>
                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>
                    <h3 class="title">Comments</h3>
                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown
                        in the comments form, and also the visitor’s IP address and browser user agent string to
                        help spam detection.</p>
                    <p>An anonymized string created from your email address (also called a hash) may be provided
                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy
                        is available here: https://automattic.com/privacy/. After approval of your comment, your
                        profile picture is visible to the public in the context of your comment.</p>
                    <h3 class="title">Media</h3>
                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading
                        images with embedded location data (EXIF GPS) included. Visitors to the website can
                        download and extract any location data from images on the website.</p>
                    <h3 class="title">Cookies</h3>
                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your
                        name, email address and website in
                        cookies. These are for your convenience so that you do not have to fill in your details
                        again when you leave another
                        comment. These cookies will last for one year.</p>
                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser
                        accepts cookies. This cookie
                        contains no personal data and is discarded when you close your browser.</p>
                    <p>When you log in, we will also set up several cookies to save your login information and
                        your screen display choices.
                        Login cookies last for two days, and screen options cookies last for a year. If you
                        select "Remember Me", your login
                        will persist for two weeks. If you log out of your account, the login cookies will be
                        removed.</p>
                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.
                        This cookie includes no personal
                        data and simply indicates the post ID of the article you just edited. It expires after 1
                        day.</p>
                    <h3 class="title">Embedded content from other websites</h3>
                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,
                        images, articles, etc.). Embedded
                        content from other websites behaves in the exact same way as if the visitor has visited
                        the other website.</p>
                    <p>These websites may collect data about you, use cookies, embed additional third-party
                        tracking, and monitor your
                        interaction with that embedded content, including tracking your interaction with the
                        embedded content if you have an
                        account and are logged in to that website.</p>
                    <p>For users that register on our website (if any), we also store the personal information
                        they provide in their user
                        profile. All users can see, edit, or delete their personal information at any time
                        (except they cannot change their
                        username). Website administrators can also see and edit that information. browser user
                        agent string to help spam detection.</p>',
                ]);
            }
        }

        $page       = new CustomizeablePage;
        $page->slug = 'privacy-policy';
        if ($page->save()) {
            foreach (allLanguages() as $language) {
                CustomizablePageTranslation::create([
                    'customizeable_page_id' => $page->id,
                    'lang_code'             => $language->code,
                    'title'                 => 'Privacy Policy',
                    'description'           => '<h3 class="title">Who we are</h3>
                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>
                    <h3 class="title">Comments</h3>
                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown
                        in the comments form, and also the visitor’s IP address and browser user agent string to
                        help spam detection.</p>
                    <p>An anonymized string created from your email address (also called a hash) may be provided
                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy
                        is available here: https://automattic.com/privacy/. After approval of your comment, your
                        profile picture is visible to the public in the context of your comment.</p>
                    <h3 class="title">Media</h3>
                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading
                        images with embedded location data (EXIF GPS) included. Visitors to the website can
                        download and extract any location data from images on the website.</p>
                    <h3 class="title">Cookies</h3>
                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your
                        name, email address and website in
                        cookies. These are for your convenience so that you do not have to fill in your details
                        again when you leave another
                        comment. These cookies will last for one year.</p>
                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser
                        accepts cookies. This cookie
                        contains no personal data and is discarded when you close your browser.</p>
                    <p>When you log in, we will also set up several cookies to save your login information and
                        your screen display choices.
                        Login cookies last for two days, and screen options cookies last for a year. If you
                        select "Remember Me", your login
                        will persist for two weeks. If you log out of your account, the login cookies will be
                        removed.</p>
                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.
                        This cookie includes no personal
                        data and simply indicates the post ID of the article you just edited. It expires after 1
                        day.</p>
                    <h3 class="title">Embedded content from other websites</h3>
                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,
                        images, articles, etc.). Embedded
                        content from other websites behaves in the exact same way as if the visitor has visited
                        the other website.</p>
                    <p>These websites may collect data about you, use cookies, embed additional third-party
                        tracking, and monitor your
                        interaction with that embedded content, including tracking your interaction with the
                        embedded content if you have an
                        account and are logged in to that website.</p>
                    <p>For users that register on our website (if any), we also store the personal information
                        they provide in their user
                        profile. All users can see, edit, or delete their personal information at any time
                        (except they cannot change their
                        username). Website administrators can also see and edit that information. browser user
                        agent string to help spam detection.</p>',
                ]);
            }
        }

        $page       = new CustomizeablePage;
        $page->slug = 'return-policy';
        if ($page->save()) {
            foreach (allLanguages() as $language) {
                CustomizablePageTranslation::create([
                    'customizeable_page_id' => $page->id,
                    'lang_code'             => $language->code,
                    'title'                 => 'Return Policy',
                    'description'           => '<h3 class="title">Who we are</h3>
                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>
                    <h3 class="title">Comments</h3>
                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown
                        in the comments form, and also the visitor’s IP address and browser user agent string to
                        help spam detection.</p>
                    <p>An anonymized string created from your email address (also called a hash) may be provided
                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy
                        is available here: https://automattic.com/privacy/. After approval of your comment, your
                        profile picture is visible to the public in the context of your comment.</p>
                    <h3 class="title">Media</h3>
                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading
                        images with embedded location data (EXIF GPS) included. Visitors to the website can
                        download and extract any location data from images on the website.</p>
                    <h3 class="title">Cookies</h3>
                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your
                        name, email address and website in
                        cookies. These are for your convenience so that you do not have to fill in your details
                        again when you leave another
                        comment. These cookies will last for one year.</p>
                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser
                        accepts cookies. This cookie
                        contains no personal data and is discarded when you close your browser.</p>
                    <p>When you log in, we will also set up several cookies to save your login information and
                        your screen display choices.
                        Login cookies last for two days, and screen options cookies last for a year. If you
                        select "Remember Me", your login
                        will persist for two weeks. If you log out of your account, the login cookies will be
                        removed.</p>
                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.
                        This cookie includes no personal
                        data and simply indicates the post ID of the article you just edited. It expires after 1
                        day.</p>
                    <h3 class="title">Embedded content from other websites</h3>
                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,
                        images, articles, etc.). Embedded
                        content from other websites behaves in the exact same way as if the visitor has visited
                        the other website.</p>
                    <p>These websites may collect data about you, use cookies, embed additional third-party
                        tracking, and monitor your
                        interaction with that embedded content, including tracking your interaction with the
                        embedded content if you have an
                        account and are logged in to that website.</p>
                    <p>For users that register on our website (if any), we also store the personal information
                        they provide in their user
                        profile. All users can see, edit, or delete their personal information at any time
                        (except they cannot change their
                        username). Website administrators can also see and edit that information. browser user
                        agent string to help spam detection.</p>',
                ]);
            }
        }

        $page       = new CustomizeablePage;
        $page->slug = 'join-as-seller';
        if ($page->save()) {
            foreach (allLanguages() as $language) {
                CustomizablePageTranslation::create([
                    'customizeable_page_id' => $page->id,
                    'lang_code'             => $language->code,
                    'title'                 => 'Join a seller',
                    'description'           => '<h3>Become an seller</h3>
                        <p>It is a long established fact that a reader will be distracted by the readable content of a
                            page when looking at its layout. The point of using Lorem Ipsum is that it has a
                            more-or-less normal distribution of letters, as opposed to using \'Content here, content
                            here, making it look like readable English.</p>

                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam
                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non
                            numquam eius modi tempora incidunt ut labore.</p>

                        <h3>Seller Rules</h3>
                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam
                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non
                            numquam eius modi tempora incidunt ut labore.</p>
                        <ul>
                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>
                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>
                            <li>Web Page Layout Design and Slider Creation</li>
                            <li>Image Insert method af web site</li>
                            <li>Creating Styling Web Pages Using CSS3</li>
                        </ul>
                        <h3>Start With Courses</h3>
                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam
                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non
                            numquam eius modi tempora incidunt ut labore.</p>
                        <ul>
                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>
                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>
                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>
                            <li>Web Page Layout Design and Slider Creation</li>
                        </ul>
                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>
                        <h3>vendor Rules</h3>
                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam
                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non
                            numquam eius modi tempora incidunt ut labore.</p>
                        <ul>
                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>
                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>
                            <li>Web Page Layout Design and Slider Creation</li>
                            <li>Image Insert method af web site</li>
                            <li>Creating Styling Web Pages Using CSS3</li>
                        </ul>
                        <h3>Start With Products</h3>
                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam
                            est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non
                            numquam eius modi tempora incidunt ut labore.</p>
                        <ul>
                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>
                            <li>Details Idea about HTMLS, Creating Basic Web Pages using HTMLS</li>
                            <li>Basic knowledge and detailed understanding of CSS3 to create.</li>
                            <li>Web Page Layout Design and Slider Creation</li>
                        </ul>
                        <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia
                            consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt.</p>',
                ]);
            }
        }

        $page       = new CustomizeablePage;
        $page->slug = 'example';
        if ($page->save()) {
            foreach (allLanguages() as $language) {
                CustomizablePageTranslation::create([
                    'customizeable_page_id' => $page->id,
                    'lang_code'             => $language->code,
                    'title'                 => 'Example Page',
                    'description'           => '<h3 class="title">Who we are</h3>
                    <p><b>Suggested text:</b> Our website address is: https://yourwebsite.com</p>
                    <h3 class="title">Comments</h3>
                    <p><b>Suggested text:</b> When visitors leave comments on the site we collect the data shown
                        in the comments form, and also the visitor’s IP address and browser user agent string to
                        help spam detection.</p>
                    <p>An anonymized string created from your email address (also called a hash) may be provided
                        to the Gravatar service to see if you are using it. The Gravatar service privacy policy
                        is available here: https://automattic.com/privacy/. After approval of your comment, your
                        profile picture is visible to the public in the context of your comment.</p>
                    <h3 class="title">Media</h3>
                    <p><b>Suggested text:</b> If you upload images to the website, you should avoid uploading
                        images with embedded location data (EXIF GPS) included. Visitors to the website can
                        download and extract any location data from images on the website.</p>
                    <h3 class="title">Cookies</h3>
                    <p><b>Suggested text:</b> If you leave a comment on our site you may opt-in to saving your
                        name, email address and website in
                        cookies. These are for your convenience so that you do not have to fill in your details
                        again when you leave another
                        comment. These cookies will last for one year.</p>
                    <p>If you visit our login page, we will set a temporary cookie to determine if your browser
                        accepts cookies. This cookie
                        contains no personal data and is discarded when you close your browser.</p>
                    <p>When you log in, we will also set up several cookies to save your login information and
                        your screen display choices.
                        Login cookies last for two days, and screen options cookies last for a year. If you
                        select "Remember Me", your login
                        will persist for two weeks. If you log out of your account, the login cookies will be
                        removed.</p>
                    <p>If you edit or publish an article, an additional cookie will be saved in your browser.
                        This cookie includes no personal
                        data and simply indicates the post ID of the article you just edited. It expires after 1
                        day.</p>
                    <h3 class="title">Embedded content from other websites</h3>
                    <p><b>Suggested text:</b> Articles on this site may include embedded content (e.g. videos,
                        images, articles, etc.). Embedded
                        content from other websites behaves in the exact same way as if the visitor has visited
                        the other website.</p>
                    <p>These websites may collect data about you, use cookies, embed additional third-party
                        tracking, and monitor your
                        interaction with that embedded content, including tracking your interaction with the
                        embedded content if you have an
                        account and are logged in to that website.</p>
                    <p>For users that register on our website (if any), we also store the personal information
                        they provide in their user
                        profile. All users can see, edit, or delete their personal information at any time
                        (except they cannot change their
                        username). Website administrators can also see and edit that information. browser user
                        agent string to help spam detection.</p>',
                ]);
            }
        }
    }
}
