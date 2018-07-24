$( document ).ready(function() {
    $('.replyButton').click(function () {
        let repliedMessage = $(this).attr('id');
        console.log(repliedMessage);
        $('.replyToMessage').val(repliedMessage);
    });
});