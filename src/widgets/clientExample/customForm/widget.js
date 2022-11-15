function loadJquery() {
    // Load the script
    const script = document.createElement("script");
    script.src = 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js';
    script.type = 'text/javascript';
    script.addEventListener('load', () => {
        exec()
    });
    document.head.appendChild(script);
}

window.onload = function () {
    if (!window.jQuery) {
        loadJquery()
    } else {
        exec()
    }
}

function exec() {
    const form = $('#amo_widget_custom_form').parent().closest('form')

    $(form).submit(function (event) {
        event.preventDefault(); //this will prevent the default submit
        $.post(
            'http://localhost:8090?client_uid=123',
            findData(form),
            function (data, status){
                $(this).unbind('submit').submit(); // continue the submit unbind preventDefault
                location.reload()
            }
        )
    });
}

function findData(form) {
    let data = {};
    let fill = function (context){
        if ($(context).attr('name') !== undefined){
            data[$(context).attr('name').toLowerCase()] = $(context).val();
        }else if($(context).attr('placeholder') !== undefined) {
            data[$(context).attr('placeholder').toLowerCase()] = $(context).val();
        }
    }
    $(form).find('input').each(function (){
        fill(this)
    })
    $(form).find('textarea').each(function (){
        fill(this)
    })
    return data
}