{* purpose of this template: inclusion template for display of related Forums in admin area *}

<h4>
    <a href="{modurl modname='MUBoard' type='admin' func='display' ot='forum' id=$item.id}">
        {$item.title}
    </a>
    <a id="forumItem{$item.id}Display" href="{modurl modname='MUBoard' type='admin' func='display' ot='forum' id=$item.id theme='Printer'}" title="{gt text='Open quick view window'}" style="display: none">
        {icon type='view' size='extrasmall' __alt='Quick view'}
    </a>
</h4>
    <script type="text/javascript" charset="utf-8">
    /* <![CDATA[ */
        document.observe('dom:loaded', function() {
            muboardInitInlineWindow($('forumItem{{$item.id}}Display'), '{{$item.title|replace:"'":""}}');
        });
    /* ]]> */
    </script>

