<div class="tab-pane {{  ( Session::get('active_tab') == 'whatsappTab') ? 'active' : '' }}"
     id="tab-8">
    <div class="p-a-md"><h5><i class="fab fa-whatsapp"></i>
            &nbsp; {!!  __('backend.whatsappChatWidget') !!}</h5></div>
    <div class="p-a-md col-md-12">

        <div class="form-group">
            <div class="radio">
                <div>
                    <label class="md-check" onclick="document.getElementById('whatsapp_phone_no').style.display='none';document.getElementById('whatsapp_no').value=''">
                        <input type="radio" name="whatsapp_no_st" value="1" class="has-value" {{ ($Setting->whatsapp_no !="")?"":"checked" }} id="whatsapp_no1">
                        <i class="danger"></i>
                        {!!  __('backend.notActive') !!}
                    </label>
                </div>
                <div style="margin-top: 5px;">
                    <label class="md-check" onclick="document.getElementById('whatsapp_phone_no').style.display='block';document.getElementById('whatsapp_no').value='#'">
                        <input type="radio" name="whatsapp_no_st" value="0" class="has-value" {{ ($Setting->whatsapp_no !="")?"checked":"" }} id="whatsapp_no2">
                        <i class="primary"></i>
                        {!!  __('backend.active') !!}
                    </label>
                </div>
            </div>
        </div>

        <div class="form-group {{ ($Setting->whatsapp_no !="")?"":"displayNone" }}" id="whatsapp_phone_no">
            <label for="whatsapp_no">{!!  __('backend.whatapp') !!}</label>
            <input type="text" autocomplete="off" name="whatsapp_no" id="whatsapp_no" value="{{ $Setting->whatsapp_no }}" placeholder="{{ __('backend.whatapp') }}" class="form-control" dir="ltr"/>
        </div>

    </div>
</div>
