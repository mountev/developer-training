<div id="custom-summary">
  {* Display Contribution *}
  <div id="custom-contributions">
    <h3>Contribution Summary</h3>
    {crmAPI var='result' entity='Contribution' action='get' contact_id=$contactId sequential=1}
    {assign var="total" value=0}
    {foreach from=$result.values item=contribution}
      {assign var="total" value=$total+$contribution.total_amount}
    {/foreach}
    <div class="crm-summary-row">
      <div class="crm-label">Total</div>
      <div class="crm-content crm-contact-contribution_summary">{$total|crmMoney}</div>
    </div>
  </div>

  {* Display Memberships *}
  <div id="custom-memberships">
    <h3>Memberships</h3>
    <table class="selector row-highlight">
    <thead class="sticky">
        <th scope="col">{ts}Membership{/ts}</th>
        <th scope="col">{ts}Member Since{/ts}</th>
        <th scope="col">{ts}Start Date{/ts}</th>
        <th scope="col">{ts}End Date{/ts}</th>
        <th scope="col">{ts}Status{/ts}</th>
    </thead>
    <tbody>
      {crmAPI var='result' entity='Membership' action='get' contact_id=$contactId sequential=1}
      <!--<pre>{$result|@print_r}</pre>-->
      {foreach from=$result.values item=membership}
        <tr>
          <td>{$membership.membership_name}</td>
          <td>{$membership.join_date|crmDate}</td>
          <td>{$membership.start_date|crmDate}</td>
          <td>{$membership.end_date|crmDate}</td>
          <td>
            {crmAPI var='statusresult' entity='MembershipStatus' action='getsingle' id=$membership.status_id sequential=1}
            {$statusresult.label}
          </td>
        </tr>
      {/foreach}
    </tbody>
    </table>
  </div>
</div>

{literal}
<script type="text/javascript">
  // Move the above content to top
  cj("#custom-summary").prependTo("#contact-summary");
</script>
{/literal}













<!--
  <div id="custom-summary">
  {* Display Contribution *}
  <div id="custom-contributions">
    <h3>Contributions</h3>
    <table class="selector row-highlight">
    <thead class="sticky">
        <th scope="col">{ts}Amount{/ts}</th>
        <th scope="col">{ts}Type{/ts}</th>
        <th scope="col">{ts}Source{/ts}</th>
        <th scope="col">{ts}Received Date{/ts}</th>
        <th scope="col">{ts}Status{/ts}</th>
    </thead>
    <tbody>
      {crmAPI var='result' entity='Contribution' action='get' contact_id=$contactId sequential=1}
      {foreach from=$result.values item=contribution}
        <tr>
          <td>{$contribution.total_amount|crmMoney}</td>
          <td>{$contribution.financial_type}</td>
          <td>{$contribution.contribution_source}</td>
          <td>{$contribution.receive_date|crmDate}</td>
          <td>{$contribution.contribution_status}</td>
        </tr>
      {/foreach}
    </tbody>
    </table>
  </div>
-->
