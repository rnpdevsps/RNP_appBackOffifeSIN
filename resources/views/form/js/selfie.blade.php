@push('script')
    <script src="{{ asset('vendor/js/webcam.min.js') }}"></script>
    <script language="JavaScript">
        function setupWebcam(camera_id) {
            try {
                Webcam.set({
                    width: 260,
                    height: 250,
                    image_format: 'jpeg',
                    jpeg_quality: 90
                });
                Webcam.attach('#' + camera_id);
            } catch (error) {
                show_toastr('{{ __('Error') }}', error.message, 'danger');
            }
        }

        $('.camera_screen').each(function() {
            setupWebcam($(this).attr('id'));
        });

        function takeSnapshot(camera_id) {
            try {
                Webcam.snap(function(data_uri) {
                    $('input[name="' + camera_id + '"]').val(data_uri);
                    $('.' + camera_id).html('<img src="' + data_uri + '"/>');
                });
            } catch (error) {
                show_toastr('{{ __('Error') }}', error.message, 'danger');
            }
        }
    </script>
@endpush
