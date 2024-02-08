<?php
/*
  Plugin Name: Gestion NaiaInvest
  Plugin URI: http://naiainvest.com
  Description: Plugin para la gestión de inmuebles de NaiaInvest
  Version: 5
  Author: Jaume Fite Planes
  Author URI: http://bububass.com
 */

/**
 * Creación de custom post inmueble
 */
add_action('init', 'crear_custom_inmueble');

function crear_custom_inmueble() {
    $labels = array(
        'name' => _x('Inmuebles', 'post type general name'),
        'singular_name' => _x('Inmueble', 'post type singular name'),
        'add_new' => _x('Añadir nuevo', 'book'),
        'add_new_item' => __('Añadir nuevo inmueble'),
        'edit_item' => __('Editar inmueble'),
        'new_item' => __('Nuevo inmueble'),
        'all_items' => __('Todos los inmuebles'),
        'view_item' => __('Ver inmueble'),
        'search_items' => __('Buscar inmueble'),
        'not_found' => __('No se han encontrado inmuebles'),
        'not_found_in_trash' => __('No se han encontrado inmuebles en la papelera'),
        'parent_item_colon' => '',
    );
    $args = array(
        'labels' => $labels,
        'description' => 'Inmuebles en venta o alquiler',
        'public' => true,
        'menu_position' => 5,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'inmueble'),
        'supports' => array('title', 'editor', 'thumbnail'),
        'has_archive' => true
    );
    register_post_type('inmueble', $args);
    //add_action('init', 'crear_custom_inmueble');
    //flush_rewrite_rules();
}

// creamos los demás campos personalizados:

add_action("admin_menu", "inmueble_init");

function inmueble_init() {
    //add_meta_box( $id, $title, $callback, $post_type, $context, $priority, $callback_args );
    add_meta_box('inmueble_textos', 'Otros textos', 'inmueble_textos_print', 'inmueble', 'normal', 'default');
    add_meta_box('inmueble_ficha', 'Ficha del inmueble', 'inmueble_ficha_print', 'inmueble', 'normal', 'default');
    add_meta_box('inmueble_localizacion', 'Localización del inmueble', 'inmueble_localizacion_print', 'inmueble', 'normal', 'default');
    add_meta_box('inmueble_privados', 'Datos privados', 'inmueble_privados_print', 'inmueble', 'normal', 'default');
    add_meta_box('inmueble_archivo', 'Archivo PDF', 'inmueble_archivo_print', 'inmueble', 'side', 'default');
    add_meta_box('inmueble_precio', 'Precio del inmueble', 'inmueble_precio_print', 'inmueble', 'side', 'default');
    add_meta_box('inmueble_destacado', 'Destacado', 'inmueble_destacado_print', 'inmueble', 'side', 'default');
}

function inmueble_archivo_print() {
    wp_nonce_field(plugin_basename(__FILE__), 'wp_custom_attachment_nonce');  
    
    $post_obj = get_post_custom($post->ID);
    $pdf1     = $post_obj["wp_custom_attachment"];
     
    $html = '<p class="description">';  
    
    if($pdf1){
        $html .= 'Archivo PDF existente';
    }else
        $html .= 'No existe PDF asociado';
    
    $html .= '</p>';  
    $html .= '<input type="file" id="wp_custom_attachment" name="wp_custom_attachment" value="" size="25">';  
      
    echo $html;  
}

function inmueble_destacado_print() {
    global $post;
    $post_obj = get_post_custom($post->ID);
    $inmueble_destacado = $post_obj["inmueble_destacado"][0];
    ?>

    <p><label for="inmueble_destacado">Destacado:</label>
        <input type="radio" name="inmueble_destacado" id="inmueble_destacado_no" value="0" <?php if ($inmueble_destacado == 0) echo 'checked="checked"' ?>>No
        <input type="radio" name="inmueble_destacado" id="inmueble_destacado_si" value="1" <?php if ($inmueble_destacado == 1) echo 'checked="checked"' ?>>Sí
    </p>
    <?php
}

function inmueble_textos_print() {
    global $post;
    $post_obj = get_post_custom($post->ID);
    //$inmueble_slogan = $post_obj["inmueble_slogan"][0];
    //$inmueble_texto_principal = $post_obj["inmueble_texto_principal"][0];
    //$currlang = get_bloginfo('language');
    
    /*
    $currlang = '';
    
    if ( get_query_var('new_lang') ) { 
        $currlang = get_query_var('new_lang'); 
    
        if($currlang = 'en'){
            $inmueble_texto_principal = $post_obj["inmueble_texto_principal_en"][0];
            $texto_principal = 'inmueble_texto_principal_en'; 
        }
    } else {
        $inmueble_texto_principal = $post_obj["inmueble_texto_principal"][0];
        $texto_principal = 'inmueble_texto_principal'; 
    }
     * 
     */
    
    /*
    echo '<BR>get_query_var: ' . get_query_var('new_lang');
    echo '<BR>get_bloginfo: ' . get_bloginfo('language');
    echo '<BR>pll_current_language: ' . pll_current_language();
    */
        $inmueble_texto_principal = $post_obj["inmueble_texto_principal"][0];
        $texto_principal = 'inmueble_texto_principal';
    ?>

    <!--<p><label for="inmueble_slogan">Slogan:</label> <textarea rows="4" cols="" name="inmueble_slogan" id="inmueble_slogan" style="width:98%"><?php //echo $inmueble_slogan; ?></textarea></p>-->
    <p><label for="<?php echo $texto_principal; ?>">Texto principal:</label> <textarea rows="4" name="<?php echo $texto_principal; ?>" id="<?php echo $texto_principal; ?>" style="width:98%"><?php echo $inmueble_texto_principal; ?></textarea></p>

    <?php
    //endif;
}

function inmueble_ficha_print() {
    global $post;
    $post_obj = get_post_custom($post->ID);
    $inmueble_referencia = $post_obj["inmueble_referencia"][0];
    $inmueble_superficie_terreno = $post_obj["inmueble_superficie_terreno"][0];
    $inmueble_superficie = $post_obj["inmueble_superficie"][0];
    $inmueble_n_plantas = $post_obj["inmueble_n_plantas"][0];
    $inmueble_n_habitaciones = $post_obj["inmueble_n_habitaciones"][0];
    $inmueble_n_dormitorios = $post_obj["inmueble_n_dormitorios"][0];
    $inmueble_n_aseos = $post_obj["inmueble_n_aseos"][0];
    $inmueble_n_banios = $post_obj["inmueble_n_banios"][0];
    $inmueble_n_parking = $post_obj["inmueble_n_parking"][0];
    $inmueble_trastero = $post_obj["inmueble_trastero"][0];
    $inmueble_terraza = $post_obj["inmueble_terraza"][0];
    $inmueble_jardin = $post_obj["inmueble_jardin"][0];
    $inmueble_detalles = $post_obj["inmueble_detalles"][0];
    $inmueble_estado = $post_obj["inmueble_estado"][0];
    $inmueble_situacion = $post_obj["inmueble_situacion"][0];
    $testimony_avatar = $post_obj["testimony_avatar"][0];
    ?>

    <p><label for="inmueble_referencia">Referencia:</label> <input type="text" name="inmueble_referencia" id="inmueble_referencia" value="<?php echo $inmueble_referencia; ?>" style="width:100px" /></p>
    <p><label for="inmueble_superficie_terreno">Superficie terreno:</label> <input type="text" name="inmueble_superficie_terreno" id="inmueble_superficie_terreno" value="<?php echo $inmueble_superficie_terreno; ?>" style="width:100px" /> m2</p>
    <p><label for="inmueble_superficie">Superficie construida:</label> <input type="text" name="inmueble_superficie" id="inmueble_superficie" value="<?php echo $inmueble_superficie; ?>" style="width:100px" /> m2</p>
    <p><label for="inmueble_n_plantas">Número de plantas:</label> <input type="text" name="inmueble_n_plantas" id="inmueble_n_plantas" value="<?php echo $inmueble_n_plantas; ?>" style="width:50px" /></p>
    <p><label for="inmueble_n_habitaciones">Número de habitaciones:</label> <input type="text" name="inmueble_n_habitaciones" id="inmueble_n_habitaciones" value="<?php echo $inmueble_n_habitaciones; ?>" style="width:50px" /></p>
    <p><label for="inmueble_n_dormitorios">Número de dormitorios:</label> <input type="text" name="inmueble_n_dormitorios" id="inmueble_n_dormitorios" value="<?php echo $inmueble_n_dormitorios; ?>" style="width:50px" /></p>
    <p><label for="inmueble_n_aseos">Número de aseos:</label> <input type="text" name="inmueble_n_aseos" id="inmueble_n_aseos" value="<?php echo $inmueble_n_aseos; ?>" style="width:50px" /></p>
    <p><label for="inmueble_n_banios">Número de baños:</label> <input type="text" name="inmueble_n_banios" id="inmueble_n_banios" value="<?php echo $inmueble_n_banios; ?>" style="width:50px" /></p>
    <p><label for="inmueble_n_parking">Plazas de párking:</label> <input type="text" name="inmueble_n_parking" id="inmueble_n_parking" value="<?php echo $inmueble_n_parking; ?>" style="width:50px" /></p>
    <p><label for="inmueble_trastero">Trastero:</label><input type="text" name="inmueble_trastero" id="inmueble_trastero" value="<?php echo $inmueble_trastero; ?>" style="width:100px" /></p>
    <p><label for="inmueble_terraza">Terraza:</label><input type="text" name="inmueble_terraza" id="inmueble_terraza" value="<?php echo $inmueble_terraza; ?>" style="width:100px" /> m2</p>
    <p><label for="inmueble_jardin">Jardín:</label><input type="text" name="inmueble_jardin" id="inmueble_jardin" value="<?php echo $inmueble_jardin; ?>" style="width:100px" /> m2</p>
        <!--<input type="radio" name="inmueble_trastero" id="inmueble_trastero_0" value="0" <?php //if ($inmueble_trastero == 0) echo 'checked="checked"' ?>>No
        <input type="radio" name="inmueble_trastero" id="inmueble_trastero_1" value="1" <?php //if ($inmueble_trastero == 1) echo 'checked="checked"' ?>>Sí-->
    </p>
    <p><label for="inmueble_estado">Estado:</label> <textarea rows="4" cols="" name="inmueble_estado" id="inmueble_estado" style="width:98%"><?php echo $inmueble_estado; ?></textarea></p>
    <p><label for="inmueble_situacion">Situación:</label> <textarea rows="4" cols="" name="inmueble_situacion" id="inmueble_situacion" style="width:98%"><?php echo $inmueble_situacion; ?></textarea></p>
    <p><label for="inmueble_detalles">Otros datos de interés:</label> <textarea rows="4" cols="" name="inmueble_detalles" id="inmueble_detalles" style="width:98%"><?php echo $inmueble_detalles; ?></textarea></p>
    <!--
    <p>
      <label for="testimony_avatar" style="margin-right: 5px">Avatar:</label><br />
      <input type="text" value="<?php echo $testimony_avatar; ?>" name="testimony_avatar" id="testimony_avatar" style="width:98%" /><br />
      <button id="testimony_avatar_trigger">Subir imagen</button>
    </p>
    <?php //if ($testimony_avatar !='') :  ?>
    <p><img src="<?php //echo $testimony_avatar;   ?>" alt="" id="testimony_avatar_image" /></p>
    -->
    <?php
    //endif;
}

function inmueble_localizacion_print() {
    global $post;
    $post_obj = get_post_custom($post->ID);
    $inmueble_pais = $post_obj["inmueble_pais"][0];
    $inmueble_region = $post_obj["inmueble_region"][0];
    $inmueble_zona = $post_obj["inmueble_zona"][0];
    $inmueble_distrito = $post_obj["inmueble_distrito"][0];
    $inmueble_poblacion = $post_obj["inmueble_poblacion"][0];
    $inmueble_cp = $post_obj["inmueble_cp"][0];
    //$inmueble_tipo_via = $post_obj["inmueble_tipo_via"][0];
    $inmueble_via = $post_obj["inmueble_via"][0];
    $inmueble_numero = $post_obj["inmueble_numero"][0];
    $inmueble_piso = $post_obj["inmueble_piso"][0];
    $inmueble_puerta = $post_obj["inmueble_puerta"][0];
    $inmueble_lat = $post_obj["inmueble_lat"][0];
    $inmueble_long = $post_obj["inmueble_long"][0];
    ?>

    <p><label for="inmueble_pais">País:</label>
        <select name="inmueble_pais" id="inmueble_pais">
            <option value="1" <?php if ($inmueble_pais == "1") echo "selected='selected'"; ?>>España</option>
        </select>
    </p>
    <p><label for="inmueble_region">Región:</label> <input type="text" name="inmueble_region" id="inmueble_region" value="<?php echo $inmueble_region; ?>" style="width:400px" /></p>
    <p><label for="inmueble_zona">Zona:</label> <input type="text" name="inmueble_zona" id="inmueble_zona" value="<?php echo $inmueble_zona; ?>" style="width:400px" /></p>
    <p><label for="inmueble_distrito">Distrito:</label> <input type="text" name="inmueble_distrito" id="inmueble_distrito" value="<?php echo $inmueble_distrito; ?>" style="width:400px" /></p>
    <p><label for="inmueble_poblacion">Población:</label> <input type="text" name="inmueble_poblacion" id="inmueble_poblacion" value="<?php echo $inmueble_poblacion; ?>" style="width:400px" /></p>
    <div class="f">
        <div class="admin_left">
            <div id="map"></div>
        </div>
        <div class="admin_right">
            <!--
            <p><label for="inmueble_tipo_via">Tipo de vía:</label>
                <select name="inmueble_tipo_via" id="inmueble_tipo_via">
                    <option value="1" <?php if ($inmueble_tipo_via == "1") echo "selected='selected'"; ?>>Calle</option>
                    <option value="2" <?php if ($inmueble_tipo_via == "2") echo "selected='selected'"; ?>>Avenida</option>
                    <option value="3" <?php if ($inmueble_tipo_via == "3") echo "selected='selected'"; ?>>Plaza</option>
                </select>
            </p>
            -->
            <p><label for="inmueble_via">Vía:</label> <input type="text" name="inmueble_via" id="inmueble_via" value="<?php echo $inmueble_via; ?>" style="width:300px" /></p>
            <p><label for="inmueble_cp">Código postal:</label> <input type="text" name="inmueble_cp" id="inmueble_cp" value="<?php echo $inmueble_cp; ?>" style="width:100px" /></p>
            <p><label for="inmueble_numero">Número:</label> <input type="text" name="inmueble_numero" id="inmueble_numero" value="<?php echo $inmueble_numero; ?>" style="width:100px" /></p>
            <p><label for="inmueble_piso">Piso:</label> <input type="text" name="inmueble_piso" id="inmueble_piso" value="<?php echo $inmueble_piso; ?>" style="width:100px" /></p>
            <p><label for="inmueble_puerta">Puerta:</label> <input type="text" name="inmueble_puerta" id="inmueble_puerta" value="<?php echo $inmueble_puerta; ?>" style="width:100px" /></p>
            <input type="text" name="inmueble_lat" id="inmueble_lat" value="<?php echo $inmueble_lat; ?>" class="hidden"/>
            <input type="text" name="inmueble_long" id="inmueble_long" value="<?php echo $inmueble_long; ?>" class="hidden"/>
        </div>
    </div>

    <?php
}

function inmueble_privados_print() {
    global $post;
    $post_obj = get_post_custom($post->ID);
    $inmueble_propietario = $post_obj["propietario"][0];
    $inmueble_propietario_pais = $post_obj["propietario_pais"][0];
    $inmueble_propietario_poblacion = $post_obj["propietario_poblacion"][0];
    $inmueble_propietario_direccion = $post_obj["propietario_direccion"][0];
    $inmueble_propietario_cp = $post_obj["propietario_cp"][0];
    $inmueble_propietario_email = $post_obj["propietario_email"][0];
    $inmueble_propietario_telefono = $post_obj["propietario_telefono"][0];
    $inmueble_propietario_observaciones = $post_obj["propietario_observaciones"][0];
    ?>

    <p><label for="propietario">Propietario:</label> <input type="text" name="propietario" id="propietario" value="<?php echo $inmueble_propietario; ?>" style="width:600px" /></p>
    <p><label for="propietario_pais">País:</label>
        <select name="propietario_pais" id="propietario_pais">
            <option value="1" <?php if ($inmueble_propietario_pais == "1") echo "selected='selected'"; ?>>España</option>
        </select>
    </p>
    <p><label for="propietario_poblacion">Población:</label> <input type="text" name="propietario_poblacion" id="propietario_poblacion" value="<?php echo $inmueble_propietario_poblacion; ?>" style="width:600px" /></p>
    <p><label for="propietario_direccion">Dirección:</label> <input type="text" name="propietario_direccion" id="propietario_direccion" value="<?php echo $inmueble_propietario_direccion; ?>" style="width:600px" /></p>
    <p><label for="propietario_cp">Código postal:</label> <input type="text" name="propietario_cp" id="propietario_cp" value="<?php echo $inmueble_propietario_cp; ?>" style="width:100px" /></p>
    <p><label for="propietario_email">Email:</label> <input type="text" name="propietario_email" id="propietario_email" value="<?php echo $inmueble_propietario_email; ?>" style="width:600px" /></p>
    <p><label for="propietario_telefono">Teléfono:</label> <input type="text" name="propietario_telefono" id="propietario_telefono" value="<?php echo $inmueble_propietario_telefono; ?>" style="width:100px" /></p>
    <p><label for="propietario_observaciones">Observaciones:</label> <textarea rows="4" cols="" name="propietario_observaciones" id="propietario_observaciones" style="width:98%"><?php echo $inmueble_propietario_observaciones; ?></textarea></p>
    <?php
}

function inmueble_precio_print() {
    global $post;
    $post_obj = get_post_custom($post->ID);
    $inmueble_precio = $post_obj["inmueble_precio"][0];
    $inmueble_regimen = $post_obj["inmueble_regimen"][0];
    ?>

    <p><label for="inmueble_regimen">Regimen:</label><br/>
        <input type="radio" name="inmueble_regimen" id="inmueble_regimen_venta" value="0" <?php if ($inmueble_regimen == 0) echo 'checked="checked"' ?>>En venta<br/>
        <input type="radio" name="inmueble_regimen" id="inmueble_regimen_compra" value="1" <?php if ($inmueble_regimen == 1) echo 'checked="checked"' ?>>En Alquiler<br/>
        <input type="radio" name="inmueble_regimen" id="inmueble_regimen_inversion" value="2" <?php if ($inmueble_regimen == 2) echo 'checked="checked"' ?>>Inversión<br/>
        <input type="radio" name="inmueble_regimen" id="inmueble_regimen_inversion" value="3" <?php if ($inmueble_regimen == 3) echo 'checked="checked"' ?>>Vendido<br/>
        <input type="radio" name="inmueble_regimen" id="inmueble_regimen_inversion" value="4" <?php if ($inmueble_regimen == 4) echo 'checked="checked"' ?>>Alquilado
       <!--<input type="text" name="inmueble_regimen" id="inmueble_regimen" value="<?php // echo $inmueble_n_plantas;    ?>" style="width:98%" />-->
    </p>
    <p><label for="inmueble_precio">Precio:</label> <input type="text" name="inmueble_precio" id="inmueble_precio" value="<?php echo $inmueble_precio; ?>" style="width:170px" /> €</p>

    <?php
}

function my_init_method() {
    //wp_deregister_script( 'jQuery' );
    //wp_register_script('jQuery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    //wp_register_script('jQueryUI', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js', array('jQuery'));
    wp_register_script('jQuery', 'https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js');
    wp_register_script('jQueryUI', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js', array('jQuery'));
    wp_enqueue_script('jQuery');
    wp_enqueue_script('jQueryUI');
}

function my_admin_scripts() {
    //wp_deregister_script( 'jquery' );
    //wp_register_script( 'jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js');
    //wp_enqueue_script( 'jquery' );

    // Register the style like this for a plugin:
    wp_enqueue_style( 'naia-style', plugins_url( 'css/style.css', __FILE__ ), array(), '1', 'all' );
    //wp_enqueue_style('naia-style');

    //wp_enqueue_script('media-upload');
    //wp_enqueue_script('thickbox');
    //wp_register_script('my-upload', get_bloginfo('template_url') . '/js/uploads.js', array('jQuery', 'media-upload', 'thickbox'));
    //wp_enqueue_script('gmaps', 'http://maps.google.com/maps/api/js?sensor=false', array('jQuery'));
    wp_enqueue_script('gmaps', 'http://maps.google.com/maps/api/js?sensor=false');
    wp_enqueue_script('index-inmueble', plugins_url( 'js/index.js', __FILE__ ), array('jQuery'));
    //wp_enqueue_script('jquery');
    //wp_enqueue_script('my-upload');
    //wp_enqueue_script('gmaps');
    //wp_enqueue_script('index-inmueble');
}
add_action('init', 'my_init_method');
add_action('admin_print_scripts', 'my_admin_scripts');

add_action('save_post', 'inmueble_textos_save');
add_action('save_post', 'inmueble_ficha_save');
add_action('save_post', 'inmueble_localizacion_save');
add_action('save_post', 'inmueble_privados_save');
add_action('save_post', 'inmueble_precio_save');
add_action('save_post', 'inmueble_destacado_save');
add_action('save_post', 'inmueble_archivo_save');

function inmueble_archivo_save() {
    global $post;
    $id = $post->ID;
    
    /* --- security verification --- */  
    if(!wp_verify_nonce($_POST['wp_custom_attachment_nonce'], plugin_basename(__FILE__))) {  
      return $id;  
    } // end if  
        
    if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {  
      return $id;  
    } // end if  
        
    if('page' == $_POST['post_type']) {  
      if(!current_user_can('edit_page', $id)) {  
        return $id;  
      } // end if  
    } else {  
        if(!current_user_can('edit_page', $id)) {  
            return $id;  
        } // end if  
    } // end if  
    /* - end security verification - */  
      
    // Make sure the file array isn't empty  
    if(!empty($_FILES['wp_custom_attachment']['name'])) { 
         
        // Setup the array of supported file types. In this case, it's just PDF.  
        $supported_types = array('application/pdf');  
          
        // Get the file type of the upload  
        $arr_file_type = wp_check_filetype(basename($_FILES['wp_custom_attachment']['name']));  
        $uploaded_type = $arr_file_type['type'];  
          
        // Check if the type is supported. If not, throw an error.  
        if(in_array($uploaded_type, $supported_types)) {  
  
            // Use the WordPress API to upload the file  
            $upload = wp_upload_bits($_FILES['wp_custom_attachment']['name'], null, file_get_contents($_FILES['wp_custom_attachment']['tmp_name']));  
      
            if(isset($upload['error']) && $upload['error'] != 0) {  
                wp_die('There was an error uploading your file. The error is: ' . $upload['error']);  
            } else {  
                //add_post_meta($id, 'wp_custom_attachment', $upload);  
                update_post_meta($id, 'wp_custom_attachment', $upload);       
            } // end if/else  
  
        } else {  
            wp_die("The file type that you've uploaded is not a PDF.");  
        } // end if/else  
          
    } // end if  
      
}

function inmueble_destacado_save() {
    global $post;
    if ($post->post_type == 'inmueble') {
        update_post_meta($post->ID, "inmueble_destacado", $_POST["inmueble_destacado"]);
    }
}

function inmueble_textos_save() {
    global $post;

    if ($post->post_type == 'inmueble') {
        //update_post_meta($post->ID, "inmueble_slogan", $_POST["inmueble_slogan"]);
        update_post_meta($post->ID, "inmueble_texto_principal", $_POST["inmueble_texto_principal"]);
    }
}

function inmueble_ficha_save() {
    global $post;

    if ($post->post_type == 'inmueble') {
        update_post_meta($post->ID, "inmueble_referencia", $_POST["inmueble_referencia"]);
        update_post_meta($post->ID, "inmueble_superficie_terreno", $_POST["inmueble_superficie_terreno"]);
        update_post_meta($post->ID, "inmueble_superficie", $_POST["inmueble_superficie"]);
        update_post_meta($post->ID, "inmueble_n_plantas", $_POST["inmueble_n_plantas"]);
        update_post_meta($post->ID, "inmueble_n_habitaciones", $_POST["inmueble_n_habitaciones"]);
        update_post_meta($post->ID, "inmueble_n_dormitorios", $_POST["inmueble_n_dormitorios"]);
        update_post_meta($post->ID, "inmueble_n_aseos", $_POST["inmueble_n_aseos"]);
        update_post_meta($post->ID, "inmueble_n_banios", $_POST["inmueble_n_banios"]);
        update_post_meta($post->ID, "inmueble_n_parking", $_POST["inmueble_n_parking"]);
        update_post_meta($post->ID, "inmueble_trastero", $_POST["inmueble_trastero"]);
        update_post_meta($post->ID, "inmueble_terraza", $_POST["inmueble_terraza"]);
        update_post_meta($post->ID, "inmueble_jardin", $_POST["inmueble_jardin"]);
        update_post_meta($post->ID, "inmueble_detalles", $_POST["inmueble_detalles"]);
        update_post_meta($post->ID, "inmueble_estado", $_POST["inmueble_estado"]);
        update_post_meta($post->ID, "inmueble_situacion", $_POST["inmueble_situacion"]);
    }
}

function inmueble_privados_save() {
    global $post;

    if ($post->post_type == 'inmueble') {
        update_post_meta($post->ID, "propietario", $_POST["propietario"]);
        update_post_meta($post->ID, "propietario_pais", $_POST["propietario_pais"]);
        update_post_meta($post->ID, "propietario_poblacion", $_POST["propietario_poblacion"]);
        update_post_meta($post->ID, "propietario_direccion", $_POST["propietario_direccion"]);
        update_post_meta($post->ID, "propietario_cp", $_POST["propietario_cp"]);
        update_post_meta($post->ID, "propietario_email", $_POST["propietario_email"]);
        update_post_meta($post->ID, "propietario_telefono", $_POST["propietario_telefono"]);
        update_post_meta($post->ID, "propietario_observaciones", $_POST["propietario_observaciones"]);
    }
}

function inmueble_localizacion_save() {
    global $post;

    if ($post->post_type == 'inmueble') {
        update_post_meta($post->ID, "inmueble_pais", $_POST["inmueble_pais"]);
        update_post_meta($post->ID, "inmueble_region", $_POST["inmueble_region"]);
        update_post_meta($post->ID, "inmueble_zona", $_POST["inmueble_zona"]);
        update_post_meta($post->ID, "inmueble_distrito", $_POST["inmueble_distrito"]);
        update_post_meta($post->ID, "inmueble_poblacion", $_POST["inmueble_poblacion"]);
        update_post_meta($post->ID, "inmueble_cp", $_POST["inmueble_cp"]);
        //update_post_meta($post->ID, "inmueble_tipo_via", $_POST["inmueble_tipo_via"]);
        update_post_meta($post->ID, "inmueble_via", $_POST["inmueble_via"]);
        update_post_meta($post->ID, "inmueble_numero", $_POST["inmueble_numero"]);
        update_post_meta($post->ID, "inmueble_piso", $_POST["inmueble_piso"]);
        update_post_meta($post->ID, "inmueble_puerta", $_POST["inmueble_puerta"]);
        update_post_meta($post->ID, "inmueble_lat", $_POST["inmueble_lat"]);
        update_post_meta($post->ID, "inmueble_long", $_POST["inmueble_long"]);
    }
}

function inmueble_precio_save() {
    global $post;
    if ($post->post_type == 'inmueble') {
        update_post_meta($post->ID, "inmueble_precio", $_POST["inmueble_precio"]);
        update_post_meta($post->ID, "inmueble_regimen", $_POST["inmueble_regimen"]);
    }
}

// creamos columnas con campos personalizados para el backoficce:
add_filter("manage_edit-inmueble_columns", "inmueble_custom_columns");
add_filter("manage_edit-inmueble_sortable_columns", "inmueble_sortable_columns");
add_action("manage_posts_custom_column", "inmueble_columns_fields");

function inmueble_custom_columns($columns) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "inmueble_referencia" => "Ref",
        "title" => "Inmueble",
        "inmueble_via" => "Vía",
        "inmueble_numero" => "Nº",
        "inmueble_superficie" => "Superficie",
        "inmueble_precio" => "Precio",
        "date" => "Fecha"
    );

    return $columns;
}

function inmueble_columns_fields($column) {
    global $post;

    if ("ID" == $column)
        echo $post->ID;
    //elseif ("inmueble" == $column) echo "<img src='" . get_post_meta($post->ID, "testimony_avatar", true) ."' style='width:35px' />";
    //elseif ("inmueble" == $column) echo $post->post_title;
    elseif ("inmueble_via" == $column)
        echo get_post_meta($post->ID, "inmueble_via", true);
    elseif ("inmueble_numero" == $column)
        echo get_post_meta($post->ID, "inmueble_numero", true);
    elseif ("inmueble_superficie" == $column)
        echo get_post_meta($post->ID, "inmueble_superficie", true) . ' m2';
    elseif ("inmueble_precio" == $column)
        echo get_post_meta($post->ID, "inmueble_precio", true) . ' €';
    elseif ("inmueble_referencia" == $column)
        echo get_post_meta($post->ID, "inmueble_referencia", true);
    //elseif ("publicado" == $column) echo $post->post_date;
}

// Ordenacion a las columnmas
function inmueble_sortable_columns($columns) {
    $columns['inmueble_via'] = 'inmueble_via';
    $columns['inmueble_numero'] = 'inmueble_numero';
    $columns['inmueble_superficie'] = 'inmueble_superficie';
    $columns['inmueble_precio'] = 'inmueble_precio';
    $columns['inmueble_referencia'] = 'inmueble_referencia';
    return $columns;
}

add_filter('parse_query', 'inmueble_columns_orderby');

//add_filter( 'request', 'inmueble_columns_orderby' );

function inmueble_columns_orderby($query) {
    global $pagenow;
    if (is_admin() && $pagenow == 'edit.php' && isset($_GET['post_type']) && $_GET['post_type'] == 'inmueble' && isset($_GET['orderby']) && $_GET['orderby'] != 'None') {
        switch ($_GET['orderby']) {
            case 'inmueble_referencia':
            case 'inmueble_via':
            case 'inmueble_numero':
            case 'inmueble_superficie':
            case 'inmueble_precio':
                //$query->query_vars['orderby'] = 'meta_value';
                $query->query_vars['orderby'] = 'meta_value_num';
                $query->query_vars['meta_key'] = $_GET['orderby'];
                break;
        }
    }
}

function update_edit_form() {  
    echo ' enctype="multipart/form-data"';  
} // end update_edit_form  
add_action('post_edit_form_tag', 'update_edit_form');  

/**
 * Mensajes para la gestión de inmuebles
 *
 * @global type $post
 * @global type $post_ID
 * @param type $messages
 * @return string
 *
  function my_updated_messages_inmueble( $messages ) {
  global $post, $post_ID;

  $messages['inmueble'] = array(
  0 => '',
  1 => sprintf( __('Inmueble actualizado. <a href="%s">Ver inmueble</a>'), esc_url( get_permalink($post_ID) ) ),
  2 => __('Custom field updated.'),
  3 => __('Custom field deleted.'),
  4 => __('Inmueble actualizado..'),
  5 => isset($_GET['revision']) ? sprintf( __('Inmueble restaurado desde la versión %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
  6 => sprintf( __('Inmueble publicado. <a href="%s">Ver inmueble</a>'), esc_url( get_permalink($post_ID) ) ),
  7 => __('Inmueble guardado.'),
  8 => sprintf( __('Inmueble enviado. <a target="_blank" href="%s">Previsualizar inmueble</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  9 => sprintf( __('Inmueble programado para: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Previsualizar inmueble</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
  10 => sprintf( __('Inmueble draft actualizado. <a target="_blank" href="%s">Previsualizar inmueble</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );
  return $messages;
  }

  add_filter( 'post_updated_messages', 'my_updated_messages_inmueble' );
 */
/**
 * Mensajes de ayuda para la gestión de inmuebles
 *
 * @param type $contextual_help
 * @param type $screen_id
 * @param type $screen
 * @return string
 *
  function my_contextual_help( $contextual_help, $screen_id, $screen ) {
  if ( 'inmueble' == $screen->id ) {

  $contextual_help = '<h2>Products</h2>
  <p>Products show the details of the items that we sell on the website. You can see a list of them on this page in reverse chronological order - the latest one we added is first.</p>
  <p>You can view/edit the details of each product by clicking on its name, or you can perform bulk actions using the dropdown menu and selecting multiple items.</p>';

  } elseif ( 'edit-inmueble' == $screen->id ) {

  $contextual_help = '<h2>Editing products</h2>
  <p>This page allows you to view/modify product details. Please make sure to fill out the available boxes with the appropriate details (product image, price, brand) and <strong>not</strong> add these details to the product description.</p>';

  }
  return $contextual_help;
  }

  add_action( 'contextual_help', 'my_contextual_help', 10, 3 );
 */

/**
 * Taxonomias de inmuebles
 */
function my_taxonomies_inmueble() {
    $labels = array(
        'name' => _x('Tipos de inmuebles', 'taxonomy general name'),
        'singular_name' => _x('Tipo de inmuebles', 'taxonomy singular name'),
        'search_items' => __('Buscar tipos de inmuebles'),
        'all_items' => __('Todos los tipos de inmuebles'),
        'parent_item' => __('Parent Product Category'),
        'parent_item_colon' => __('Parent Product Category:'),
        'edit_item' => __('Editar tipo de inmueble'),
        'update_item' => __('Actualizar tipo de inmueble'),
        'add_new_item' => __('Añadir nuevo tipo de inmueble'),
        'new_item_name' => __('Nuevo tipo de imueble'),
        'menu_name' => __('Tipos de inmueble')
    );

    $rewrite = array(
        'slug' => 'tipos', // This controls the base slug that will display before each term
        'with_front' => false, // Don't display the category base before "/locations/"
        'hierarchical' => true // This will allow URL's like "/locations/boston/cambridge/"
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
        'rewrite' => $rewrite
    );

    register_taxonomy('inmueble_category', 'inmueble', $args);
}

add_action('init', 'my_taxonomies_inmueble', 0);
?>