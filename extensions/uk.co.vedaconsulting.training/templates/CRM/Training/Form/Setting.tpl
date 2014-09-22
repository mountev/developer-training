<div class="crm-block crm-form-block crm-mailchimp-setting-form-block">
  <div class="crm-accordion-wrapper crm-accordion_mailchimp_setting-accordion crm-accordion-open">
    <div class="crm-accordion-header">
      <div class="icon crm-accordion-pointer"></div> 
      {ts}Training Settings{/ts}
    </div><!-- /.crm-accordion-header -->
    <div class="crm-accordion-body">

      <table class="form-layout-compressed">
    	  <tr class="crm-mailchimp-setting-api-key-block">
          <td class="label">{$form.display_membership.label}</td>
          <td>{$form.display_membership.html}
          </td>
        </tr>
        <tr class="crm-mailchimp-setting-security-key-block">
          <td class="label">{$form.display_contribution_total.label}</td>
          <td>{$form.display_contribution_total.html}
          </td>
        </tr> 
      </table>
    </div>
    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl"}
    </div>
  </div>
</div>
