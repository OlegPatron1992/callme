<div id="_desktop_callme_link">
    <div id="callme-link">
        <a href="#" data-toggle="modal" data-target="#callme-modal">
            {l s=$pagelinktext mod='callme'}
            {if $pagehelptooltiptext}
                <i class="material-icons" data-toggle="tooltip" data-placement="top"
                   title="{l s=$pagehelptooltiptext mod='callme'}">notifications_active</i>
            {/if}
        </a>
    </div>
</div>
<div id="callme-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="callMeModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="callMeModalLabel">{l s=$modaltitle mod='callme'}</h4>
            </div>
            <div class="modal-body">
                <p>{l s=$modaldescription mod='callme'}</p>
                <form id="callme-form" method="get" action="#" novalidate>
                    <div class="form-fields container">
                        <div class="row">
                            <div class="col-sm-9">
                                <div class="form-group row">
                                    <label for="callmeName"
                                           class="col-sm-2 col-form-label">{l s='Name' mod='callme'}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm" id="callmeName"
                                               placeholder="{l s='Required' mod='callme'}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="callmePhone"
                                           class="col-sm-2 col-form-label">{l s='Phone' mod='callme'}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control form-control-sm" id="callmePhone"
                                               placeholder="{l s='Required, e.g. +380001234567' mod='callme'}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="callmeEmail"
                                           class="col-sm-2 col-form-label">{l s='Email' mod='callme'}</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control form-control-sm" id="callmeEmail"
                                               aria-describedby="callmeEmailHelp"
                                               placeholder="{l s='Optional' mod='callme'}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="callmeMessage"
                                           class="col-sm-2 col-form-label">{l s='Message' mod='callme'}</label>
                                    <div class="col-sm-10">
                                <textarea class="form-control form-control-sm" id="callmeMessage" rows="3"
                                          placeholder="{l s='Optional, your question shortly' mod='callme'}"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <img class="callme-support-img" src="{$supportimage}"/>
                            </div>
                        </div>
                    </div>
                    <div class="form-footer text-sm-right">
                        <button id="callme-submit" type="submit"
                                class="btn btn-primary">{l s='Submit' mod='callme'}</button>
                    </div>
                </form>
                <div id="callme-success" class="alert alert-success" style="display: none;" role="alert">
                    {l s='Request successfully sent!' mod='callme'}
                </div>
                <div id="callme-failed" class="alert alert-danger" style="display: none;" role="alert">
                    {l s='Something went wrong...' mod='callme'}
                </div>
            </div>
        </div>
    </div>
</div>