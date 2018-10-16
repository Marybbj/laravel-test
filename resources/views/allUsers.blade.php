<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">

<style>
.fa {
  cursor: pointer;
}
</style>
</head>
<body>
<h1>Users</h1>
<div class="row">
<div class="col-md-12">

        <div class="form-group">
            <label for="name">Search:</label>
            <input type="text" class="form-control" id="search" name="search">
        </div>
 
        
        
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">First</th>
      <th scope="col">Last</th>
      <th scope="col">Age</th>
      <th scope="col">CV</th>
      <th scope="col">Actions</th>
    </tr>
  </thead>
  <tbody >
  @foreach ($allUsers as $key =>  $user)
    <tr>
      <th scope="row">{!!$user['id'] !!}</th>
      <td>{!! $user['name'] !!}</td>
      <td>{!! $user['surname'] !!}</td>
      <td>{!! $user['age'] !!}</td>
      <td><a href="{!! $user['cv'] !!}">Download CV</a></td>
      <td class="remove-user" data-id="{!! $user['id'] !!}"><i style="color: blue" class="fa fa-trash"></i></td>
    </tr>
    
  @endforeach
    
    
  </tbody>
</table>
</div>

<script>
const postSendAjax = function(url, data, success, error) {
    $.ajax({
        type: "post",
        url: url,
        cache: false,
        datatype: "json",
        data: data,
        headers: {
            "X-CSRF-TOKEN": $("meta[name='csrf-token']").attr("content")
        },
        success: function(data) {
            if (success) {
                success(data);
            }
            return data;
        },
        error: function(errorThrown) {
            if (error) {
                error(errorThrown);
            }
            return errorThrown;
        }
    });
};
$("body").on('click', ".remove-user", function (e) {
  let id = $(this).attr("data-id");
  postSendAjax("/delete-user", {id: id}, function (res) {
    if (!res.error) {
      $("body").find(`[data-id="${id}"]`).closest("tr").remove()
    }
    
  })
})
function htmlMaker(data) {
  return `<tr>
      <th scope="row">${data.id}</th>
      <td>${data.name}</td>
      <td>${data.surname}</td>
      <td>${data.age}</td>
      <td><a href="${data.cv}">Download CV</a></td>
      <td class="remove-user" data-id="${data.id}"><i style="color: blue" class="fa fa-trash"></i></td>
    </tr>`
}
$("body").on("input", "#search", function (e) {
  e.preventDefault()
  let id = $(this).val();
  postSendAjax("/search-user", {id: id}, function (res) {
    if (!res.error) {
      $("tbody").empty()
      res.data.forEach(item => {
        $("tbody").append(htmlMaker(item))
      })
    }
    
  })
})

</script>
</body>
</html>