<?php
defined( 'ABSPATH' ) || exit;

abstract class OPTNNO_Field {
    abstract public static function render($dev_name, $field, $currentValue, $index = "");
}

class OPTNNO_Field_Text extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $value = !empty($currentValue) ? $currentValue : '';
        ?>
        <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
        <input type="text" class="optionino-input" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"
               value="<?php echo esc_attr($value); ?>">
        <p><?php echo esc_html($desc); ?></p>
        <?php
    }
}

class OPTNNO_Field_Number extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $value = !empty($currentValue) ? $currentValue : '';
        ?>
        <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
        <input type="number" class="optionino-input" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"
               value="<?php echo esc_attr($value); ?>">
        <p><?php echo esc_html($desc); ?></p>
        <?php
    }
}

class OPTNNO_Field_Textarea extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $value = !empty($currentValue) ? $currentValue : '';
        ?>
        <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
        <textarea class="optionino-input" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"><?php echo esc_textarea($value); ?></textarea>
        <p><?php echo esc_html($desc); ?></p>
        <?php
    }
}

class OPTNNO_Field_Color extends OPTNNO_Field {
    public static function render($dev_name, $field, $current_value, $index = '') {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'] . $index : '';
        $value = !empty($current_value) ? $current_value : '';
        ?>
        <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
        <input type="text" class="optionino-color-selector" id="<?php echo esc_attr($name); ?>" placeholder="#RRGGBB" pattern="^#?[0-9A-Fa-f]{3,8}$" name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>">
        <p><?php echo esc_html($desc); ?></p>
        <?php
    }
}

class OPTNNO_Field_Select extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();
        echo '<label class="optionino-form-label" for="' . esc_attr($name) . '">' . esc_html($title) . '</label>';
        echo '<select class="optionino-select" id="' . esc_attr($name) . '" name="' . esc_attr($name) . '">';

        foreach ($options as $key => $label) {
            $selected = ($currentValue == $key) ? 'selected' : '';
            echo '<option value="' . esc_attr($key) . '" ' . $selected . '>' . esc_html($label) . '</option>';
        }

        echo '</select>';
        echo '<p>' . esc_html($desc) . '</p>';
    }
}

class OPTNNO_Field_Buttonset extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $options = !empty($field['options']) && is_array($field['options']) ? $field['options'] : array();
        $value = !empty($currentValue) ? $currentValue : '';
        echo '<label class="optionino-form-label">' . esc_html($title) . '</label>';
        echo '<div class="optionino-button-set-box flex">';

        foreach ($options as $key => $label) {
            $id = esc_attr($name . '_' . $key);
            $checked = ($key == $value) ? 'checked' : '';
            echo '<input type="radio" class="optionino-radio button-set" id="' . $id . '" name="' . esc_attr($name) . '" value="' . esc_attr($key) . '" ' . $checked . '>';
            echo '<label class="optionino-button-label flex" for="'.$id.'">' . esc_html($label) . '</label>';
        }

        echo '</div>';
        echo '<p>' . esc_html($desc) . '</p>';
    }
}

class OPTNNO_Field_Switcher extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'] . $index : '';
        $value = filter_var($currentValue, FILTER_VALIDATE_BOOLEAN);
        $checked = $value ? 'checked' : '';

        echo '<label class="optionino-form-label">' . esc_html($title) . '</label>';
        echo '<div class="optionino-switch-box flex">';
        $id = $name;

        echo '<input type="hidden" name="' . esc_attr($name) . "_filled" . '" value="false">';

        echo '<input type="checkbox" class="optionino-switch-checkbox optionino-radio" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="true" ' . $checked . '>';
        echo '<label class="optionino-switch-label" for="' . esc_attr($id) . '"></label>';
        echo '</div>';
        echo '<p>' . esc_html($desc) . '</p>';
    }
}

class OPTNNO_Field_Image extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'] . $index : '';
        $value = !empty($currentValue) ? $currentValue : '';
        ?>
        <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
        <div class="optionino-box-image-field">
            <div class="inner-image-box-optionino">
                <input type="text" class="optionino-input image-url" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>"
                       value="<?php echo esc_attr($value); ?>">
                <input type="button" class="optionino-button upload-image-button" data-image-field="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr__('Upload Image', OPTNNO_TEXTDOMAIN); ?>">
                <p><?php echo esc_html($desc); ?></p>
            </div>
            <img id="<?php echo $name; ?>-preview" class="uploaded-image optionino-image-preview" src="<?php echo esc_url($value); ?>" style="max-width: 100%;">
        </div>
        <?php
    }
}

class OPTNNO_Field_Tinymce extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $value = !empty($currentValue) ? $currentValue : '';
        ?>
        <label class="optionino-form-label" for="<?php echo esc_attr($name); ?>"><?php echo esc_html($title); ?></label>
        <textarea id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" class="optionino-input"><?php echo esc_textarea($value); ?></textarea>
        <p><?php echo esc_html($desc); ?></p>
        <?php wp_enqueue_editor(); ?>
        <script>
            jQuery(document).ready(function($){
                wp.editor.initialize('<?php echo $name; ?>', {
                    tinymce: {
                        wpautop: true,
                        toolbar: 'formatselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | charmap | pastetext | removeformat | undo redo | wp_adv',
                        wrap_lines: true,
                        tabfocus_elements: ':prev,:next',
                        toolbar1: 'styleselect formatselect fontselect fontsizeselect',
                        toolbar2: 'cut copy paste | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link unlink | charmap | pastetext | removeformat | undo redo | wp_adv',
                        toolbar3: 'table | hr | wp_more | wp_page | wp_pagebreak | visualchars | visualblocks | code',
                        toolbar4: 'fullscreen | wp_adv | wp_fullscreen',
                    },
                    quicktags: {
                        buttons: "b,i,ul,ol,li,link,close"
                    }
                });
            });
        </script>
        <?php
    }
}

class OPTNNO_Field_Repeater extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $fields = !empty($field['fields']) && is_array($field['fields']) ? $field['fields'] : array();
        echo '<div class="optionino-repeater-field" data-repeater-name="' . esc_attr($name) . '">';
        echo '<label class="optionino-form-label">' . esc_html($title) . '</label>';
        echo '<div class="optionino-repeater-container">';
        if (empty($currentValue) || !is_array($currentValue)) {
            $currentValue = [array_fill_keys(array_column($fields, 'id'), '')];
        }
        foreach ($currentValue as $index => $item) {
            echo '<div class="optionino-repeater-item" data-item-index="' . esc_attr($index) . '">';
            echo '<div class="optionino-repeater-seperate-subfields">';
            foreach ($fields as $subfield) {
                $fieldValue = isset($item[$subfield['id']]) ? $item[$subfield['id']] : '';
                OPTNNO_Builder::field_option($dev_name, $subfield, true, $index ,$fieldValue);
            }
            echo '</div>';
            echo '<div class="optionino-repeater-seperate-subbuttons">';
            echo '<button class="optionino-remove-repeater-item"><svg width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="#f00" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M4 7h16" />
              <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
              <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
              <path d="M10 12l4 4m0 -4l-4 4" />
            </svg>' . __('Delete', OPTNNO_TEXTDOMAIN) . ' </button>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
        echo '<button class="optionino-add-repeater-item">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus" width="25" height="25" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
              <path d="M12 5l0 14" />
              <path d="M5 12l14 0" />
            </svg>
            </button>';
        echo '</div>';
        echo '<p>' . esc_html($desc) . '</p>';
    }
}

class OPTNNO_Field_Post_select extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $post_type = !empty($field['post_type']) ? $field['post_type'] : 'post';
        $transient_key = 'optnno_posts_' . md5($post_type);
        $posts = get_transient($transient_key);
        if ($posts === false) {
            $posts = get_posts(array(
                'post_type' => $post_type,
                'numberposts' => -1,
                'post_status' => 'publish'
            ));
            set_transient($transient_key, $posts, 12 * HOUR_IN_SECONDS);
        }
        
        echo '<label class="optionino-form-label" for="' . esc_attr($name) . '">' . esc_html($title) . '</label>';
        echo '<select class="optionino-select" id="' . esc_attr($name) . '" name="' . esc_attr($name) . '">';
        echo '<option value="">' . esc_html__('Select...', OPTNNO_TEXTDOMAIN) . '</option>';
        foreach ($posts as $post) {
            $selected = ($currentValue == $post->ID) ? 'selected' : '';
            echo '<option value="' . esc_attr($post->ID) . '" ' . $selected . '>' . esc_html($post->post_title) . '</option>';
        }
        echo '</select>';
        echo '<p>' . esc_html($desc) . '</p>';
    }
}

class OPTNNO_Field_Taxonomy_select extends OPTNNO_Field {
    public static function render($dev_name, $field, $currentValue, $index = "") {
        $title = !empty($field['title']) ? $field['title'] : '';
        $desc = !empty($field['desc']) ? $field['desc'] : '';
        $name = !empty($field['id']) ? $field['id'].$index : '';
        $taxonomy = !empty($field['taxonomy']) ? $field['taxonomy'] : 'category';
        $transient_key = 'optnno_terms_' . md5($taxonomy);
        $terms = get_transient($transient_key);
        if ($terms === false) {
            $terms = get_terms(array(
                'taxonomy' => $taxonomy,
                'hide_empty' => false,
            ));
            set_transient($transient_key, $terms, 12 * HOUR_IN_SECONDS);
        }
        
        echo '<label class="optionino-form-label" for="' . esc_attr($name) . '">' . esc_html($title) . '</label>';
        echo '<select class="optionino-select" id="' . esc_attr($name) . '" name="' . esc_attr($name) . '">';
        echo '<option value="">' . esc_html__('Select...', OPTNNO_TEXTDOMAIN) . '</option>';
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $selected = ($currentValue == $term->term_id) ? 'selected' : '';
                echo '<option value="' . esc_attr($term->term_id) . '" ' . $selected . '>' . esc_html($term->name) . '</option>';
            }
        }
        echo '</select>';
        echo '<p>' . esc_html($desc) . '</p>';
    }
}
