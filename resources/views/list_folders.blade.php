<html>
<head>
    <title>Demo</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
</head>
<body>
<i id="loading" class="fas fa-spin fa-spinner"></i> <h3 id="info" ></h3>
<table id="table" class="table" style="display: none;">
    <thead>
    <th>File</th>
    <th>Action</th>
    </thead>

</table>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script>
    $('#info').html('get files');
    $.getJSON('/api/files', function (data) {
        $('#info').html('parsing');
        var items = [];
        $.each(data, function (key, val) {
            items.push("<tr id='" + key + "'> <td>" + val + "</td><td><a href='/download/"+key+"'><i class='fas fa-download'></i></a></tr>");
        });
        $("<tbody/>", {
            "class": "my-new-list",
            html: items.join("")
        }).appendTo("#table");
        $('#loading').fadeOut();
        $('#table').fadeIn();
        $('#info').fadeOut();
    });
</script>
</body>
</html>