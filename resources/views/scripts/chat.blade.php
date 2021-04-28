@section('sub-script')
<script type="text/javascript">

let $userAvatar = '{!! Auth::user()->avatar !!}';
let $userId = '{!! Auth::user()->user_id !!}'

    $(".messages").animate({ scrollTop: $(document).height() }, "fast");
    
    $('.contact').on('click', function() {
        let $to = $(this).data('attr');
        location.href = "/chats?user=" + $to;
    });

    $(".expand-button").click(function() {
        $("#profile").toggleClass("expanded");
        $("#contacts").toggleClass("expanded");
    });
    
    $("#status-options ul li").click(function() {
        $("#status-online").removeClass("active");
        $("#status-away").removeClass("active");
        $("#status-busy").removeClass("active");
        $("#status-offline").removeClass("active");
        $(this).addClass("active");
        $("#status-options").removeClass("active");
    });
    
    function newMessage() {
        message = $(".message-input input").val();
        if($.trim(message) == '') {
            return false;
        }

        // save via ajax
        let _token = $('meta[name="csrf-token"]').attr('content');
        let $to = $('#to').val();
        $.ajax({
            type: "POST",
            url: "{{ route('chat.message') }}",
            data:{
                msg : message,
                user_id : $userId,
                to : $to,
                _token: _token
            },
            success:function(response){
                if(response) {
                    $('<li class="sent"><img src="'+$userAvatar+'" alt="" /><p>' + message + '</p></li>').appendTo($('.messages ul'));
                } else {
                    alert('Please choose first from your contacts!');
                }

                $('.message-input input').val(null);
                $(".messages").animate({ scrollTop: $(document).height() }, 1500);
            },
        }); 
    };
    
    $('.submit').click(function() {
        newMessage();
    });
    
    $(window).on('keydown', function(e) {
        if (e.which == 13) {
            newMessage();
            return false;
        }
    });
    </script>
@endsection