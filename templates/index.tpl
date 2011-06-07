<ul>
    {foreach $pages as $page}
    <li><a href="{jurl "kiwi~default:show", array('page' => $page->name)}">{$page->name}</a></li>
    {/foreach}
</ul>
