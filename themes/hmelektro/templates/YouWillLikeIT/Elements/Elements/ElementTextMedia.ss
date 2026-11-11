<div class="text-media text-media--image-$ImagePosition">
    <div class="row g-4 g-lg-5 align-items-center">
        <% if $Image.exists %>
        <div class="col-lg-6 text-media__media<% if $ImagePosition == 'right' %> order-lg-2<% end_if %>">
            <figure class="text-media__figure mb-0">
                <% with $Image.FillMax(1040,880) %>
                <img class="text-media__image" src="$URL" width="$Width" height="$Height" alt="$Up.ImageAltText.ATT"<% if $Up.IsFirstElement %> fetchpriority="high"<% else %> loading="lazy"<% end_if %> decoding="async">
                <% end_with %>
            </figure>
        </div>
        <% end_if %>
        <div class="<% if $Image.exists %>col-lg-6<% else %>col-lg-10 col-xl-8<% end_if %> text-media__body">
            <% if $Eyebrow %><p class="eyebrow">$Eyebrow</p><% end_if %>
            $Heading('text-media__title')
            <% if $Content %><div class="typography">$Content</div><% end_if %>
            <% include CtaButtons Tone=$ButtonTone, Class='mt-4' %>
        </div>
    </div>
</div>
