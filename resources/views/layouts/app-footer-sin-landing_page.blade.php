

      <!--scripts start here-->
      <script src="{{ asset('vendor/landing-page2/js/jquery.min.js') }}"></script>
      <script src="{{ asset('vendor/landing-page2/js/slick.min.js') }}"></script>
      <script src="{{ asset('vendor/landing-page2/js/custom.js') }}"></script>
      <!--scripts end here-->

      <script>
          function myFunction() {
              const element = document.body;
              element.classList.toggle("dark-mode");
              const isDarkMode = element.classList.contains("dark-mode");
              const expirationDate = new Date();
              expirationDate.setDate(expirationDate.getDate() + 30);
              document.cookie = `mode=${isDarkMode ? "dark" : "light"}; expires=${expirationDate.toUTCString()}; path=/`;
              if (isDarkMode) {
                  $('.switch-toggle').find('.switch-moon').addClass('d-none');
                  $('.switch-toggle').find('.switch-sun').removeClass('d-none');
              } else {
                  $('.switch-toggle').find('.switch-sun').addClass('d-none');
                  $('.switch-toggle').find('.switch-moon').removeClass('d-none');
              }
          }
          window.addEventListener("DOMContentLoaded", () => {
              const modeCookie = document.cookie.split(";").find(cookie => cookie.includes("mode="));
              if (modeCookie) {
                  const mode = modeCookie.split("=")[1];
                  if (mode === "dark") {
                      $('.switch-toggle').find('.switch-moon').addClass('d-none');
                      $('.switch-toggle').find('.switch-sun').removeClass('d-none');
                      document.body.classList.add("dark-mode");
                  } else {
                      $('.switch-toggle').find('.switch-sun').addClass('d-none');
                      $('.switch-toggle').find('.switch-moon').removeClass('d-none');
                  }
              }
          });
      </script>
      @stack('script')

      @if (Utility::getsettings('cookie_setting_enable') == 'on')
          @include('layouts.cookie-consent')
      @endif
      </body>

      </html>
