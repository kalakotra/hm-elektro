<%-- Element buttons. Tone (wrapper modifier, styled in SCSS): light (default) | dark | brand --%>
<% if $SortedCTALinks.exists %>
<div class="cta-buttons cta-buttons--<% if $Tone %>$Tone<% else %>light<% end_if %><% if $Class %> $Class<% end_if %>">
    <% loop $SortedCTALinks %>
    <a class="btn <% if $IsFirst %>cta-btn-main<% else %>cta-btn-alt<% end_if %>" href="$URL"<% if $OpenInNew %> target="_blank" rel="noopener"<% end_if %>><% if $ClassName.ShortName == 'PhoneLink' %><% include Icon Name='phone', Class='icon-sm' %><% end_if %>$Title</a>
    <% end_loop %>
</div>
<% end_if %>
