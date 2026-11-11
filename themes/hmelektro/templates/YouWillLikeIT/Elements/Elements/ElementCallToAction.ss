<div class="cta cta--$Variant">
    <div class="cta__text">
        <% if $Eyebrow %><p class="eyebrow">$Eyebrow</p><% end_if %>
        $Heading('cta__title')
        <% if $Content %><p class="cta__lead">$Content</p><% end_if %>
    </div>
    <% include CtaButtons Tone=$ButtonTone, Class='cta__actions' %>
</div>
