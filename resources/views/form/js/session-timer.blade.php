@push('script')
    <script>
        let timerInterval;
        let formId      = '{{ $form->id }}';
        let formValueId = '{{ $formValue->id ?? null }}';
        let sessionId   = null;
        let elapsedTime = 0;
        let tabIsActive = true;
        let isPaused    = false;
        let isOpen      = true;
        let formType    = $('#form_type').val();

        function startTimer() {
            $.ajax({
                url: '{{ route('timer.start') }}',
                method: 'POST',
                data: {
                    form_id: formId,
                    form_value_id: formValueId,
                    form_type: formType,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    loadTimerState();
                },
                error: function(error) {}
            });
        }

        function startTimerInterval() {
            timerInterval = setInterval(function() {
                if (!isPaused) {
                    elapsedTime++;
                    updateTimerUI(elapsedTime);
                }
            }, 1000);
        }

        function stopTimer() {
            clearInterval(timerInterval);
            $.ajax({
                url: '{{ route('timer.stop') }}',
                method: 'POST',
                data: {
                    session_id: sessionId,
                    form_value_id: formValueId,
                    form_type: formType,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    loadTimerState();
                },
                error: function(error) {}
            });
        }

        function loadTimerState() {
            $.ajax({
                url: '{{ route('timer.loadState') }}',
                method: 'POST',
                data: {
                    form_id: formId,
                    form_value_id: formValueId,
                    form_type: formType,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    sessionId = response.session_id;
                    elapsedTime = response.elapsed_time;
                    updateTimerUI(elapsedTime);
                    if (response.status === 'running') {
                        startTimerInterval();
                    } else if (response.status === 'paused' && !isPaused) {
                        recordBreakEnd();
                    } else if (response.status === 'stopped') {
                        if (isOpen) {
                            Swal.fire({
                                title: '{{ __('Do you want to start the timer?') }}',
                                text: "{{ __('Your session will be tracked if you start the timer.') }}",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: '{{ __('Yes, Start Timer!') }}',
                                cancelButtonText: '{{ __('No, Go Back') }}',
                                allowOutsideClick: false
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: '{{ route('timer.start') }}',
                                        method: 'POST',
                                        data: {
                                            form_id: formId,
                                            form_value_id: formValueId,
                                            form_type: formType,
                                            _token: $('meta[name="csrf-token"]')
                                                .attr('content')
                                        },
                                        success: function(response) {
                                            loadTimerState();
                                        },
                                        error: function(error) {}
                                    });
                                } else {
                                    @if (auth()->check())
                                        @if (isset($formValue->id))
                                            window.location.href =
                                                '{{ route('view.form.values', $form->id) }}';
                                        @else
                                            window.location.href =
                                                '{{ route('forms.index') }}';
                                        @endif
                                    @else
                                        window.location.href =
                                            '{{ route('landingpage') }}';
                                    @endif
                                }
                            });
                        }
                    }
                },
                error: function(error) {}
            });
        }

        function updateTimerUI(elapsedTime) {
            elapsedTime = isNaN(elapsedTime) ? 0 : elapsedTime;
            let hours = Math.floor(elapsedTime / 3600);
            let minutes = Math.floor((elapsedTime % 3600) / 60);
            let seconds = Math.floor(elapsedTime % 60);
            $('.hours').text(String(hours).padStart(2, '0'));
            $('.minutes').text(String(minutes).padStart(2, '0'));
            $('.seconds').text(String(seconds).padStart(2, '0'));
        }

        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden' && tabIsActive) {
                tabIsActive = false;
                recordBreakStart();
            } else if (document.visibilityState === 'visible' && !tabIsActive) {
                tabIsActive = true;
                recordBreakEnd();
            }
        });

        function recordBreakStart() {
            isPaused = true;
            clearInterval(timerInterval);
            $.ajax({
                url: '{{ route('timer.break.start') }}',
                method: 'POST',
                data: {
                    session_id: sessionId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    loadTimerState();
                },
                error: function(error) {}
            });
        }

        function recordBreakEnd() {
            isPaused = false;
            $.ajax({
                url: '{{ route('timer.break.end') }}',
                method: 'POST',
                data: {
                    session_id: sessionId,
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    loadTimerState();
                },
                error: function(error) {}
            });
        }
        loadTimerState();
    </script>
@endpush
