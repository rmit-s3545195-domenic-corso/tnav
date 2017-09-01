<div class="restroom_input">
    <div class="form-group">
        <label for="rr_name">Name</label>
        <input type="text" name="rr_name" class="form-control" id="rr_name" value="{{ Request::old('rr_name') ? Request::old('rr_name') : $restroom->name }}" required />
    </div>
    <div class="form-group">
        <label for="rr_desc">Short Description</label>
        <input type="text" name="rr_desc" class="form-control" id="rr_desc" value="{{ Request::old('rr_desc') ? Request::old('rr_desc') : $restroom->description }}" />
    </div>
    <div class="form-group">
        <label>Location</label>
        <div style="margin-bottom: 10px">
            <button class="btn btn-success" type="button" id="ri_use_loc_btn">
                <span class="glyphicon glyphicon-record"></span>
                Use my location
            </button>
        </div>
        <div id="ri_map"></div>
    </div>
    <!-- these inputs should be hidden in production -->
    <input type="text" name="rr_lat" class="form-control" style="display: none" id="rr_lat" value="{{ Request::old('rr_lat') ? Request::old('rr_lat') : $restroom->lat }}" required />
    <input type="text" name="rr_lng" class="form-control" style="display: none" id="rr_lng" value="{{ Request::old('rr_lng') ? Request::old('rr_lng') : $restroom->lng }}" required />
    <div class="form-group">
        <label for="rr_floor">Optional - Floor Number</label>
        <input type="text" name="rr_floor" class="form-control" id="rr_floor" value="{{ Request::old('rr_floor') ? Request::old('rr_floor') : $restroom->floor }}" />
    </div>
    <div class="form-group">
        <label for="rr_added_by">Optional - Your Name</label>
        <input type="text" name="rr_added_by" class="form-control" id="rr_added_by" value="{{ Request::old('rr_added_by') ? Request::old('rr_added_by') : $restroom->addedBy }}" />
    </div>
    <div class="form-group">
        <label for="rr_added_by">Optional - Upload Images</label>
        <div class="alert alert-info">
            <strong> File Type must be of type: jpg, png | Only 3 files are allowed</strong>
        </div>
        <input type="file" name="rr_photos[]" id="rr_photos[]" multiple="multiple" accept=".jpg,.png">
    </div>
    <div class="form-group" style="text-align: right">
        <button type="submit" class="btn btn-info">Save Changes</button>
    </div>
</div>
