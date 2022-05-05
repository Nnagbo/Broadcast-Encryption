<div class="modal fade" id="messageUserModal{{ $key }}" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content bbn-modal">
                    <form method="post" id="compose-modal" action="{{ url('send-message')}}">
                    {{ csrf_field() }}
                        <div class="modal-header text-left">
                            <div class="text-right">
                                <button class="glyphicon glyphicon-remove" data-dismiss="modal" style="border:0px;"></button>
                            </div>
                            <div class="text-left">
                                <h4 class="modal-title">Send Message</h4>
                            </div>
                        </div>
                        <div class="modal-body">
                            <div class="form-group ">
                                <input class="form-control" type="text" name="subject" id="subject" placeholder="Subject" required="required" value="{{ old('subject') }}" />
                            </div><br />

                            <div class="form-group ">
                                <textarea class="form-control" name="message" id="message" placeholder="Type your message..." required="required"></textarea>
                                <input type="hidden" name="ssh" id="ssh" value="{{ $user->id }}" />
                            </div><br />
                        </div>
                        <div class="modal-footer">

                            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                            <button type="submit" class="btn btn-info pull-left" id="delete-notification-confirm" >Send</button>

                            <button type="button" class="btn btn-modal-save"
                                    data-dismiss="modal" >Cancel</button>
                        </div>
                    </form>
                    </div>
                </div>
            </div>