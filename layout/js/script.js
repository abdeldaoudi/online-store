$(function(){


    $('[placeholder]').focus(function(){
        $(this).attr('data',$(this).attr('placeholder'));
        $(this).attr('placeholder','');
    });
    $('[placeholder]').blur(function(){
        $(this).attr('placeholder', $(this).attr('data'));
    })

    $('input').each(function(){
        if($(this).attr('required') == 'required')
            $(this).after('<span class="etoile">*</span>');
    });

    /*  Show Login Or Signup form*/

    $('h1 span').click(function()
    {
        $(this).addClass('selected').siblings().removeClass('selected');
        $('.login-page form').hide();
        $('.' + $(this).data('class')).fadeIn(100);
    })

    //confirmation Message on Button
    $(".confirm").click(function(){
        /*r = confirm("Are You Sure ?");
        if(r == false)
            $(this).attr('href','members.php');*/
        return confirm("Are You Sure ?");
    });
    $(".activate-btn").parent().parent().css('color','rgba(0,0,0,.5)');

    //add item function

    $(".live-name").keyup(function()
    {
        $(".live-preview .card-body h5").text($(this).val());
    });

    $(".live-desc").keyup(function()
    {
        $(".live-preview .card-body p").text($(this).val());
    });

    $(".live-price").keyup(function()
    {
        $(".live-preview .price").text($(this).val());
    });

    /*$(".live-img").on('change',function()
    {
        console.log($(this).val());
        var fileName = $(this).val().replace('C:\\fakepath\\', "");
        $(".live-preview img").attr('src', fileName);
    });*/

    $('#inputGroupFile02').on('change',function(){
        //get the file name
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");   //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })

});