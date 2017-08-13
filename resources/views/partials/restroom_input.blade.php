<div class="restroom_input">
    <div class="form-group">
        <label for="rr_name">Name</label>
        <input type="text" name="rr_name" class="form-control" id="rr_name" required />
    </div>
    <div class="form-group">
        <label for="rr_desc">Short Description</label>
        <input type="text" name="rr_desc" class="form-control" id="rr_desc" />
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
    <input type="text" name="rr_lat" class="form-control" id="rr_lat" style="display: block" required />
    <input type="text" name="rr_lng" class="form-control" id="rr_lng" style="display: block" required />
    <div class="form-group">
        <label for="rr_floor">Optional - Floor Number</label>
        <input type="text" name="rr_floor" class="form-control" id="rr_floor" />
    </div>
    <div class="form-group">
        <label for="rr_added_by">Optional - Your Name</label>
        <input type="text" name="rr_added_by" class="form-control" id="rr_added_by" />
    </div>
    <div class="form-group" style="text-align: right">
        <button type="submit" class="btn btn-info">Save Changes</button>
    </div>
</div>
