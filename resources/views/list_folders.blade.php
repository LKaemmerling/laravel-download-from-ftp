<html>
<head>
    <title>Demo</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<i id="loading" class="fas fa-spin fa-spinner"></i>
<h3 id="info"></h3>
<div id="file-browser">

</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>
    .out {
        display: none;
    }
</style>
<script>
    $('#info').html('get files');
    $.getJSON('/files', function (data) {
        $('#info').html('parsing');
        var list = "<ul class='list-group'>";
        $.each(data, function (__key, __val) {
            recurse(__key, __val, true,true);
        });

        function recurse(key, val, collapse = true, first = false) {
            list += "<li class='list-group-item collapse " + (collapse ? 'in' : 'out') + " " + (first ? '' : 'clicked') + "'>";
            if (val instanceof Object) {
                list += "<span onclick=toggleChildren(this)><i class='fas fa-caret-down'></i> " + key + "</span>" + "<ul class='list-group out parent'>";
                $.each(val, function (_key, _val) {
                    recurse(_key, _val, false)
                });
                list += "</ul>";
            } else {
                list += "<span>" + key + "</span> <a href='/download/"+val+"'><i class='pull-right fas fa-download'></i></a>";
            }
            list += "</li>";
        }

        list += "</ul>";
        $('#file-browser').html(list);
        $('#loading').fadeOut();
        $('#table').fadeIn();
        $('#info').fadeOut();
    });

    function toggleChildren(elm) {
        console.log('clicked');
        if ($(elm).parent().hasClass('clicked')) {
            $(elm).parent().find('.list-group.parent').removeClass('in').addClass('out');
            $(elm).parent().find('.list-group > li').removeClass('in').addClass('out');
            $(elm).parent().removeClass('clicked');
        } else {
            $(elm).parent().find('.list-group.parent').removeClass('out').addClass('in');
            $(elm).parent().find('.list-group > li').removeClass('out').addClass('in');
            $(elm).parent().addClass('clicked')
        }
    }
</script>
</body>
</html>