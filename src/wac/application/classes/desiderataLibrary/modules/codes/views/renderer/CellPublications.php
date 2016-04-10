<?php
class desiderataLibrary_modules_codes_views_renderer_CellPublications extends org_glizy_components_render_RenderCell
{
    function renderCell($key, $value, $row)
    {
        $value = glz_maybeJsonDecode($value, false);
        if (!is_string($value)) {
            $output = array();
            foreach($value as $v) {
                $output[] = $v->text;
            }
            return implode('<br>', $output);
        }

        return '';
    }
}


