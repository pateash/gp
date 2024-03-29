<form action="#" id="form_sample_1" class="form-horizontal" novalidate="novalidate" _lpchecked="1">
    <div class="form-body">
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
        <div class="alert alert-success display-hide">
            <button class="close" data-close="alert"></button> Your form validation is successful! </div>
        <div class="form-group">
            <label class="control-label col-md-3">Name
                <span class="required" style="color:darkred" aria-required="true"> * </span>
            </label>
            <div class="col-md-4">

                <div class="input-group">
                    <span class="input-group-addon">
                                                            <i class="fa fa-user"></i>
                                                        </span>

                <input type="text" name="name" data-required="1" class="form-control"
                   style="background-image: url(&quot;data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGP6zwAAAgcBApocMXEAAAAASUVORK5CYII=&quot;);
                   cursor: pointer;"  value="<?php  echo $this->session->userdata('USER_NAME') ?>">
            </div>
                </div>
        </div>
        <!--<div class="form-group">
            <label class="control-label col-md-3">Email
                <span class="required" aria-required="true" style="color:darkred"> * </span>
            </label>
            <div class="col-md-4">
                <input name="email" type="text" class="form-control"
                       value="<?php /* echo $this->session->userdata('USER_MAIL') */?>">

            </div>
        </div>
       -->
        <div class="form-group">
            <label class="control-label col-md-3">Email
                <span class="required" style="color:darkred" aria-required="true"> * </span>
            </label>
            <div class="col-md-4">
                <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-envelope"></i>
                                                        </span>
                    <input type="text" class="form-control" name="input_group" placeholder="Email Address"
                           value="<?php  echo $this->session->userdata('USER_MAIL') ?>">

                </div>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Website
                <span class="required" aria-required="false">  </span>
            </label>
            <div class="col-md-4">
                <div class="input-group">

                     <span class="input-group-addon">
                                                            <i class="fa fa-internet-explorer"></i>
                                                        </span>

                <input name="url" type="text" class="form-control"
                       value="<?php  echo $this->session->userdata('USER_WEBSITE') ?>">
                </div>
                <span class="help-block"> e.g: http://www.demo.com or http://demo.com </span>

              </div>
        </div>
        <div class="form-group">
            <label class="control-label col-md-3">Phone Number
                <span class="required" aria-required="true"> * </span>
            </label>
            <div class="col-md-4">
                <div class="input-group">

                     <span class="input-group-addon">
                                                            <i class="fa fa-phone"></i>
                                                        </span>

                <input name="number" type="text" class="form-control"
                       value="<?php  echo $this->session->userdata('USER_PHONE') ?>">
                </div>
        </div>
           </div>
           <div class="form-group">
            <label class="control-label col-md-3">Occupation&nbsp;&nbsp;</label>
            <div class="col-md-4">
                <input name="occupation" type="text" class="form-control">
                <span class="help-block"> optional field </span>
            </div>
        </div>
        <!-- IF WANTED TO HAVE A DROP DOWNLIST
        <div class="form-group">
            <label class="control-label col-md-3">Select
                <span class="required" aria-required="true"> * </span>
            </label>
            <div class="col-md-4">
                <select class="form-control" name="select">
                    <option value="">Select...</option>
                    <option value="Category 1">Category 1</option>
                    <option value="Category 2">Category 2</option>
                    <option value="Category 2">Category 2</option>
                    <option value="Category 3">Category 5</option>
                    <option value="Category 4">Category 4</option>
                </select>
            </div>
        </div>
        -->
        <!--<div class="form-group">
               A LIST SELECT
            <label class="control-label col-md-3">Multi Select
                <span class="required" aria-required="true"> * </span>
            </label>
            <div class="col-md-4">
                <select class="form-control" name="select_multi" multiple="" aria-required="true" aria-invalid="false" aria-describedby="select_multi-error">
                    <option value="Category 1">Category 1</option>
                    <option value="Category 2">Category 2</option>
                    <option value="Category 3">Category 3</option>
                    <option value="Category 4">Category 4</option>
                    <option value="Category 5">Category 5</option>
                </select>
                <span class="help-block"> select max 3 options, min 1 option </span>
            </div>
        </div>
        -->

    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-3 col-md-9">
              
                <button type="submit" class="btn green">Submit</button>
                <button type="button" class="btn grey-salsa btn-outline">Cancel</button>
            </div>
        </div>
    </div>
</form>