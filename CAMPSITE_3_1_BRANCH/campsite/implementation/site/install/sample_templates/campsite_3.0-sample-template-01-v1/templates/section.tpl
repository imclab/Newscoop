{{ include file="html_header.tpl" }}
<table class="main" cellspacing="0" cellpadding="0">
<tr>
  <td valign="top">
    <div id="breadcrumb">
    {{ breadcrumb }}
    </div>
    {{** main content area **}}
    <table class="content" cellspacing="0" cellpadding="0">
    <tr>
      <td>
        {{ include file="section-content.tpl" }}
      </td>
    </tr>
    </table>
    {{** end main content area **}}
  </td>
  <td valign="top">
    {{ include file="html_rightbar.tpl" }}
  </td>
</tr>
</table>
{{ include file="html_footer.tpl" }}
