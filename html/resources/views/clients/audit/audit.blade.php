@if($audit)
    <div class="tab-pane" id="Audit">
        <div class="col-lg-5">

            <label class="control-label col-sm-5"> Status </label>
            <div class="col-md-6">
                <input type="text" name="status" value="<?PHP if($audit->status == 1){echo 'Authorized';}else{echo 'Unauthorize';} ?>" class="form-control" readonly />
            </div>

            <label class="control-label col-sm-5">Inputer</label>
            <div class="col-md-6">
                <input type="text" name="" value="{{$audit->audit1->name}}" class="form-control" readonly />
            </div>
            <label class="control-label col-sm-5">Input date</label>
            <div class="col-md-6">
                <input type="text" name="" value="{{$audit->created_at}}" class="form-control" readonly />
            </div>
            @if((int)$audit->status == 1)
            <label class="control-label col-sm-5"> Authorizer </label>
            <div class="col-md-6">
                <input type="text" name="authorizer" value="{{$audit->audit2->name}}" class="form-control"  readonly/>
            </div>
            <label class="control-label col-sm-5"> Authorize date </label>
            <div class="col-md-6">
                <input type="text" name="" value="{{$audit->updated_at}}" class="form-control" readonly />
            </div>
            @endif
        </div>
    </div>
@endif