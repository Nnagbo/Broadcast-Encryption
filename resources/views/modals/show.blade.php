<div class="modal fade" id="showEnc{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content bbn-modal">
                    <form method="post" id="compose-modal">
                        <div class="modal-header text-left">
                            <div class="text-right">
                                <button class="glyphicon glyphicon-remove" data-dismiss="modal" style="border:0px;"></button>
                            </div>
                            <div class="text-left">
                                <h4 class="modal-title">Show Message</h4>
                            </div>
                        </div>
                        <div class="modal-body">
                            <textarea >{{ $message->encrypted_message }}</textarea>
                        </div>
                        <div class="modal-footer">

                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                            <!-- <button type="button" class="btn btn-danger btn-recharge" id="delete-notification-confirm" data-dismiss="modal" >Delete</button> -->
                            

                            <button type="button" class="btn btn-modal-save pull-left"
                                    data-dismiss="modal" >Close</button>
        </form>
                        </div>
                    </form>
                    </div>
                </div>
            </div>