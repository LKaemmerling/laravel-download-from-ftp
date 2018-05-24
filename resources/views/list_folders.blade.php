<html>
<head>
    <title>Demo</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css"/>
</head>
<body>
<i id="loading" class="fas fa-spin fa-spinner"></i>
<h3 id="info"></h3>
<div id="file-browser">

</div>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
<style>
    .out {
        display: none;
    }
</style>

<script>
    $.jstree.defaults.core.themes.variant = "large";

    $('#info').html('get files');
    $.getJSON('/files', function (data) {
        $('#info').html('parsing');
        var list = "<ul>";
        $.each(data, function (__key, __val) {
            recurse(__key, __val, true, true);
        });

        function recurse(key, val, collapse = true, first = false) {

            if (val instanceof Object) {
                list += "<li>";
                list += "" + key + "<ul>";
                $.each(val, function (_key, _val) {
                    recurse(_key, _val, false)
                });
                list += "</ul>";
            } else {
                list += "<li data-jstree='{\"icon\":\"fas fa-download\"}'  data-download='/download/" + val + "'>";
                list += "<span>" + key + "</span>";
            }
            list += "</li>";
        }

        list += "</ul>";
        $('#file-browser').html(list);
        $('#file-browser').jstree();
        $('#loading').fadeOut();
        $('#table').fadeIn();
        $('#info').fadeOut();
        $('#file-browser').on("select_node.jstree", function (e, data) {
            if ($('#' + data.node.id).attr('data-download') != undefined) {
                var link = document.createElement("a");
                link.download = $('#' + data.node.id).attr('data-download');
                link.href = $('#' + data.node.id).attr('data-download');
                console.log(link);
                link.click();
            }
        });
    });
</script>
</body>
</html>