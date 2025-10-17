@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
@endpush

<div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

        <div class="modal-dialog" role="document">
            <div class="modal-content" style="width: 860px; right: 140px;">
                <div class="modal-header">
                    <h5 class="modal-title">Edit image <span class="title"></span></h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img class="imageSelected img-thumbnail" alt="">
                        </div>
                        <div class="col-lg-6">

                            <div class="table-responsive">
                                <table class="table table-centered table-borderless table-striped mb-0">
                                    <tbody>
                                        <tr>
                                            <td style="width: 35%;">File name</td>
                                            <td><input id="nameImage" style="border: none;" /></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%;">Alt image</td>
                                            <td><input id="altImage" style="border-color: white;" /></td>
                                        </tr>
                                        <tr>
                                            <td style="width: 35%;">Description</td>
                                            <td><textarea id="descImage" style="border-color: white;"></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>Date uploaded</td>
                                            <td><span id="dateUpload"></span></td>
                                        </tr>
                                        <tr>
                                            <td>File size</td>
                                            <td><span id="sizeImage"></span>MB</td>
                                        </tr>
                                        <tr>
                                            <td>Model attached</td>
                                            <td><span id="modelAttached"></span></td>
                                        </tr>
                                        <tr>
                                            <td id="copySrc" style="cursor: pointer; color: red">Copy</td>
                                            <td><input id="srcImage" style="border-color: white;" /></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" id="btnUpdateImage" class="btn btn-primary">Update</button>
                    <button type="button" id="btnDeleteImage" data-toggle="modal" data-target="#delete" class="btn btn-danger">Delete</button>
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
</div>