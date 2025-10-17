<script>
    function checkAddressItem(baseId, itemClass) {
        var baseCheck = $('#' + baseId).is(":checked");
        $('.' + itemClass).each(function() {
            if (!$(this).is(':disabled')) {
                $(this).prop('checked', baseCheck);
            }
        });
    }

    function deleteCheckedAddressItem() {
        let arrayMediaIds = [];
        $('input:checkbox.itemAddress').each(function () {
            var sThisVal = (this.checked ? $(this).val() : "");
            if (sThisVal) {
                arrayMediaIds.push(sThisVal);
            }
        });
        if (arrayMediaIds.length > 0) {
            $.ajax({
                url: adminPath + '/customer/customer/' + JSON.stringify(arrayMediaIds),
                method: 'DELETE',
                data: {
                    "_token": "{{ csrf_token() }}"
                },
                success: function (response) {
                    location.reload();
                },
                error: function (e) {
                    console.log(e)
                }
            });
        } else {
            alert('Please choose at least a item.')
        }
    }

</script>

<div class="tab-content mt-3">
    <div class="tab-pane fade show active" id="customerItem">
        <div class="row">
            <div class="col-12 col-md-6">
                @input(['name' => 'name', 'label' => __('customer::customer.name')])
                @input(['name' => 'email', 'label' => __('customer::customer.email')])
                @input(['name' => 'phone', 'label' => __('customer::customer.phone')])

                @checkbox(['name' => 'is_active', 'label' => __('customer::customer.is_active'), 'default' => true])
                @select(['name' => 'group_id', 'allowClear' => true, 'label' => __('customer::customer.group'), 'options' => get_customer_group_options()])

                <hr>

                @input(['name' => 'password', 'label' => __('customer::customer.password'), 'type' => 'password'])
                @input(['name' => 'password_confirmation', 'label' => __('customer::customer.password_confirmation'), 'type' => 'password'])
            </div>
            <div class="col-12 col-md-6">
                @mediafile(['name' => 'avatar', 'label' => __('customer::customer.avatar')])
                @select(['name' => 'gender', 'allowClear' => false, 'label' => __('customer::customer.gender.label'), 'options' => [
                    ['value' => '1', 'label' => __('customer::customer.gender.male')],
                    ['value' => '0', 'label' => __('customer::customer.gender.female')],
                ]])
            </div>
        </div>
    </div>
</div>
