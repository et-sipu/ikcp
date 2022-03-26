<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <title>Tasks Report</title>
  <style type="text/css">
    table { page-break-inside:auto }
    tr    { page-break-inside:auto; page-break-after:avoid }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
  </style>
</head>
<body>
<table class="table">
  <thead>
  <tr>
    <th scope="col" width="5%">#</th>
    <th scope="col" width="45%">Task Details</th>
    <th scope="col" width="50%">Progress</th>
  </tr>
  </thead>
  <tbody>
  @foreach($tasks as $task)
    <tr>
      <th scope="row">{{$loop->index+1}}</th>
      <td>
        <div class="card">
          <div class="card-header">
            <span style="float: left"><h4>Title: {{$task->title}}</h4></span>
            <span style="float: right; text-align: right">
              Assigned Department: {{$task->assigned_department->name}}<br/>
            </span>
          </div>
          <div class="card-body">
            {!! $task->description !!}
          </div>
          <div class="card-footer">
            <span style="float: left">{!! $task->created_at->format('d M, Y') !!}</span>
            <span style="float: right" class="badge bg-primary">{{ strtoupper($task->status) }} @ {{ $task->transition($task->status) ? $task->transition($task->status)->in_date : ''}}</span>
          </div>
        </div>
      </td>
      <td>
        @foreach($task->comments as $comment)
          <div class="media">
            <div class="media-body">
              <h5 class="media-heading">{{$comment->commented->name}}<small style="float: right"><i>{{$comment->created_at->format('d M, Y')}}</i></small></h5>
              <p>{{$comment->comment}}</p>
            </div>
          </div>
        @endforeach
      </td>
    </tr>
  @endforeach
  </tbody>
</table>
</body>
</html>