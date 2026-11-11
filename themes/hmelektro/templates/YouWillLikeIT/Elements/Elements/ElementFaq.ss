<div class="faq faq--$Layout">
    <div class="row g-4 g-lg-5">
        <% if $Heading || $Intro || $Eyebrow %>
        <header class="<% if $Layout == 'intro-left' %>col-lg-5<% else %>col-lg-9 col-xl-8 mx-auto text-lg-center<% end_if %> faq__head">
            <% if $Eyebrow %><p class="eyebrow">$Eyebrow</p><% end_if %>
            $Heading('faq__title')
            <% if $Intro %><div class="typography faq__intro">$Intro</div><% end_if %>
            <% if $Layout == 'intro-left' && $ChipList %>
            <ul class="chips list-unstyled">
                <% loop $ChipList %><li>$Text</li><% end_loop %>
            </ul>
            <% end_if %>
            <% if $Layout == 'intro-left' %><% include CtaButtons Tone=$ButtonTone, Class='mt-4' %><% end_if %>
        </header>
        <% end_if %>
        <% if $SortedItems.exists %>
        <div class="<% if $Layout == 'intro-left' %>col-lg-7<% else %>col-lg-9 col-xl-8 mx-auto<% end_if %>">
            <div class="faq__items">
                <% loop $SortedItems %>
                <details class="faq__item"<% if $IsFirst %> open<% end_if %> id="faq-$Up.ID-$ID">
                    <summary class="faq__question"><span>$Question</span><% include Icon Name='plus', Class='faq__toggle' %></summary>
                    <div class="faq__answer typography">$Answer</div>
                </details>
                <% end_loop %>
            </div>
            <% if $Layout != 'intro-left' %><% include CtaButtons Tone=$ButtonTone, Class='justify-content-lg-center mt-4' %><% end_if %>
        </div>
        <% end_if %>
    </div>
</div>
