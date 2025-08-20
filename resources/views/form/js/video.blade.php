@push('script')
    <script>
        var recordedChunks = [];
        var hours = 0;
        var mins = 0;
        var seconds = 0;

        $(document).on('click', "#videostream", function() {
            var videoStream = $(this).parents('.video-stream');
            videoStream.find(".cam-buttons").fadeIn(500);
            videoStream.find('.cam-buttons').removeClass('d-none');
        });

        $(document).on('click', '#start', function() {
            var mediaName = $(this).parents('.cam-buttons').find('.multi-media').attr('name');
            var mediaWebName = $(this).parents('.cam-buttons').find('.web-cam-container');
            var buttons = $(this).parents('.cam-buttons');
            mediaWebName.removeClass('d-none');
            startRecording(mediaName, mediaWebName, buttons);
        });

        $(document).on('click', '#stop', function() {
            var mediaWebName = $(this).parents('.cam-buttons').find('.web-cam-container');
            var buttons = $(this).parents('.cam-buttons');
            mediaWebName.addClass('d-none');
            stopRecording(mediaWebName, buttons);
        });

        async function startRecording(mediaName, mediaWebName, buttons) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: true,
                    audio: true
                });
                mediaWebName[0].srcObject = stream;
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = function(event) {
                    if (event.data.size > 0) {
                        recordedChunks.push(event.data);
                    }
                };
                mediaRecorder.onstop = async function() {
                    const blob = new Blob(recordedChunks, {
                        type: 'video/webm'
                    });
                    const formData = new FormData();
                    formData.append('media', blob, 'webcam_video.webm');

                    try {
                        const processedUrlResponse = await $.ajax({
                            url: '{{ route('videostore') }}',
                            method: 'POST',
                            data: formData,
                            processData: false,
                            contentType: false,
                        });
                        $('input[name="' + mediaName + '"]').val(processedUrlResponse.filename);
                        show_toastr('{{ __('Success') }}', '{{ __('Video uploaded successfully') }}',
                            'success');
                    } catch (error) {
                        show_toastr('{{ __('Error') }}', error, 'danger');
                    }
                };

                mediaRecorder.start();
                startTimer(buttons);
                buttons.find('.start').prop('disabled', true);
                buttons.find('.stop').prop('disabled', false);
            } catch (error) {
                show_toastr('Error!', 'Camera device not found.', 'danger');
            }
        }

        function stopRecording(mediaWebName, buttons) {
            mediaRecorder.stop();
            clearTimeout(timex);
            buttons.find('#stop').attr('disabled', true);
            mediaWebName.addClass('d-none');
            resetTimer(buttons)
        }

        function resetTimer(buttons) {
            hours = 0;
            mins = 0;
            seconds = 0;
        }

        function startTimer(buttons) {
            timex = setTimeout(function() {
                seconds++;
                if (seconds > 59) {
                    seconds = 0;
                    mins++;
                    if (mins > 59) {
                        mins = 0;
                        hours++;
                        if (hours < 10) {
                            buttons.find('#timer #hours').text('0' + hours + ':')
                        } else {
                            buttons.find('#timer #hours').text(hours + ':');
                        }
                    }

                    if (mins < 10) {
                        buttons.find("#timer #mins").text('0' + mins + ':');
                    } else {
                        buttons.find("#timer #mins").text(mins + ':');
                    }
                }

                if (seconds < 10) {
                    buttons.find("#timer #seconds").text('0' + seconds);
                } else {
                    buttons.find("#timer #seconds").text(seconds);
                }
                startTimer(buttons);
            }, 1000);
        }
    </script>
@endpush

