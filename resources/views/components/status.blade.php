@props(['status'])
@if($status=='Approved' || $status=='Yes' || $status=='Active' )
<div class="badge bg-label-success me-1 ">{{$status}}</div>
@elseif($status=='Cancelled' || $status=='Rejected' || $status=='No' || $status=='In-Active')
<div class="badge bg-label-danger me-1">{{$status}}</div>
@elseif($status=='Pending' || $status=='Waiting')
<div class="badge bg-label-warning me-1">{{$status}}</div>
@endif