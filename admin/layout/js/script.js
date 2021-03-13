$(function(){

    $(".toggle-info").click(function(){
        $(this).toggleClass("selected").parent().next(".card-body").fadeToggle(100);

        if($(this).hasClass("selected"))
            $(this).html('<i class="fas fa-plus"></i>');
        else
            $(this).html('<i class="fas fa-minus"></i>');
    });

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
    $(".show-pass").hover(function(){
        $(".password").attr('type','text');
    },function(){
        $(".password").attr('type','password');
    });

    //confirmation Message on Button
    $(".confirm").click(function(){
        /*r = confirm("Are You Sure ?");
        if(r == false)
            $(this).attr('href','members.php');*/
        return confirm("Are You Sure ?");
    });
    $(".activate-btn").parent().parent().css('color','rgba(0,0,0,.5)');

    $(".category .cat h3").click(function(){
        $(this).next('.full-view').fadeToggle(200);
    });

    $('#inputGroupFile02').on('change',function(){
        //get the file name
        var fileName = $(this).val().replace('C:\\fakepath\\', " ");   //replace the "Choose a file" label
        $(this).next('.custom-file-label').html(fileName);
    })
});