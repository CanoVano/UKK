<?php

$filename = "jualansayur.drawio";
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<mxfile host="app.diagrams.net" modified="2024-01-01T00:00:00.000Z" agent="Mozilla/5.0" version="21.0.0" pages="4">' . "\n";

// Helper function to generate a standard cell
function createCell($id, $value, $style, $x, $y, $w, $h, $parent = "1") {
    return '        <mxCell id="'.$id.'" value="'.htmlspecialchars($value).'" style="'.$style.'" vertex="1" parent="'.$parent.'">
          <mxGeometry x="'.$x.'" y="'.$y.'" width="'.$w.'" height="'.$h.'" as="geometry" />
        </mxCell>' . "\n";
}

function createEdge($id, $source, $target, $style, $value = "") {
    return '        <mxCell id="'.$id.'" value="'.htmlspecialchars($value).'" style="'.$style.'" edge="1" parent="1" source="'.$source.'" target="'.$target.'">
          <mxGeometry relative="1" as="geometry" />
        </mxCell>' . "\n";
}

function createTable($id, $title, $attributes, $x, $y) {
    $html = '<table style="width:100%;font-size:12px;border-collapse:collapse;"><tr><th style="border:1px solid #ccc;background:#eee;padding:4px;">'.$title.'</th></tr>';
    foreach($attributes as $attr) {
        $html .= '<tr><td style="border:1px solid #ccc;padding:4px;">'.$attr.'</td></tr>';
    }
    $html .= '</table>';
    
    return '        <mxCell id="'.$id.'" value="'.htmlspecialchars($html).'" style="text;html=1;strokeColor=#ccc;fillColor=#ffffff;overflow=fill;rounded=0;" vertex="1" parent="1">
          <mxGeometry x="'.$x.'" y="'.$y.'" width="200" height="'.(25 + count($attributes)*25).'" as="geometry" />
        </mxCell>' . "\n";
}

// ---------------------------------------------------------
// DIAGRAM 1: USE CASE
// ---------------------------------------------------------
$xml .= '  <diagram id="usecase" name="USE CASE">' . "\n";
$xml .= '    <mxGraphModel dx="1000" dy="1000" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="827" pageHeight="1169" math="0" shadow="0">' . "\n";
$xml .= '      <root>' . "\n";
$xml .= '        <mxCell id="0" />' . "\n";
$xml .= '        <mxCell id="1" parent="0" />' . "\n";

// Actors
$xml .= createCell("actor_cust", "Customer", "shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;", 100, 300, 30, 60);
$xml .= createCell("actor_admin", "Admin", "shape=umlActor;verticalLabelPosition=bottom;verticalAlign=top;html=1;", 700, 300, 30, 60);

// Use Cases
$usecases = [
    "uc1" => ["Register & Login", 350, 100],
    "uc2" => ["Melihat Produk", 350, 200],
    "uc3" => ["Mengelola Keranjang", 350, 300],
    "uc4" => ["Checkout Pesanan", 350, 400],
    "uc5" => ["Melihat Riwayat Pesanan", 350, 500],
    "uc6" => ["Mengelola Kategori", 350, 600],
    "uc7" => ["Mengelola Produk & Varian", 350, 700],
    "uc8" => ["Mengelola Status Pesanan", 350, 800],
    "uc9" => ["Mengelola Pengguna", 350, 900]
];

foreach ($usecases as $id => $data) {
    $xml .= createCell($id, $data[0], "ellipse;whiteSpace=wrap;html=1;", $data[1], $data[2], 140, 70);
}

// Connections
$xml .= createEdge("e_c1", "actor_cust", "uc1", "endArrow=none;");
$xml .= createEdge("e_c2", "actor_cust", "uc2", "endArrow=none;");
$xml .= createEdge("e_c3", "actor_cust", "uc3", "endArrow=none;");
$xml .= createEdge("e_c4", "actor_cust", "uc4", "endArrow=none;");
$xml .= createEdge("e_c5", "actor_cust", "uc5", "endArrow=none;");

$xml .= createEdge("e_a1", "actor_admin", "uc1", "endArrow=none;");
$xml .= createEdge("e_a6", "actor_admin", "uc6", "endArrow=none;");
$xml .= createEdge("e_a7", "actor_admin", "uc7", "endArrow=none;");
$xml .= createEdge("e_a8", "actor_admin", "uc8", "endArrow=none;");
$xml .= createEdge("e_a9", "actor_admin", "uc9", "endArrow=none;");

$xml .= '      </root>' . "\n";
$xml .= '    </mxGraphModel>' . "\n";
$xml .= '  </diagram>' . "\n";

// ---------------------------------------------------------
// DIAGRAM 2: ERD
// ---------------------------------------------------------
$xml .= '  <diagram id="erd" name="ERD">' . "\n";
$xml .= '    <mxGraphModel dx="1000" dy="1000" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="1169" pageHeight="827" math="0" shadow="0">' . "\n";
$xml .= '      <root>' . "\n";
$xml .= '        <mxCell id="0" />' . "\n";
$xml .= '        <mxCell id="1" parent="0" />' . "\n";

$entities = [
    "erd_user" => ["User", 100, 300],
    "erd_cart" => ["Cart", 300, 150],
    "erd_order" => ["Order", 300, 450],
    "erd_orderitem" => ["OrderItem", 550, 450],
    "erd_variant" => ["ProductVariant", 550, 150],
    "erd_product" => ["Product", 800, 150],
    "erd_category" => ["Category", 1050, 150]
];

foreach ($entities as $id => $data) {
    $xml .= createCell($id, $data[0], "rounded=0;whiteSpace=wrap;html=1;fillColor=#dae8fc;strokeColor=#6c8ebf;", $data[1], $data[2], 120, 60);
}

// ERD Relations (Lines)
$xml .= createEdge("rel1", "erd_user", "erd_cart", "endArrow=none;", "1..N");
$xml .= createEdge("rel2", "erd_user", "erd_order", "endArrow=none;", "1..N");
$xml .= createEdge("rel3", "erd_variant", "erd_cart", "endArrow=none;", "1..N");
$xml .= createEdge("rel4", "erd_order", "erd_orderitem", "endArrow=none;", "1..N");
$xml .= createEdge("rel5", "erd_variant", "erd_orderitem", "endArrow=none;", "1..N");
$xml .= createEdge("rel6", "erd_product", "erd_variant", "endArrow=none;", "1..N");
$xml .= createEdge("rel7", "erd_category", "erd_product", "endArrow=none;", "1..N");

$xml .= '      </root>' . "\n";
$xml .= '    </mxGraphModel>' . "\n";
$xml .= '  </diagram>' . "\n";

// ---------------------------------------------------------
// DIAGRAM 3: CDM
// ---------------------------------------------------------
$xml .= '  <diagram id="cdm" name="CDM">' . "\n";
$xml .= '    <mxGraphModel dx="1000" dy="1000" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="1169" pageHeight="827" math="0" shadow="0">' . "\n";
$xml .= '      <root>' . "\n";
$xml .= '        <mxCell id="0" />' . "\n";
$xml .= '        <mxCell id="1" parent="0" />' . "\n";

$tablesCDM = [
    "cdm_user" => ["USER", ["id &lt;pk&gt;", "name", "email", "password", "phone", "role"], 50, 300],
    "cdm_cart" => ["CART", ["id &lt;pk&gt;", "user_id &lt;fk&gt;", "product_variant_id &lt;fk&gt;", "quantity"], 300, 100],
    "cdm_order" => ["ORDER", ["id &lt;pk&gt;", "user_id &lt;fk&gt;", "order_number", "total_price", "status", "pickup_time"], 300, 500],
    "cdm_orderitem" => ["ORDER_ITEM", ["id &lt;pk&gt;", "order_id &lt;fk&gt;", "product_variant_id &lt;fk&gt;", "quantity", "price", "subtotal"], 550, 500],
    "cdm_variant" => ["PRODUCT_VARIANT", ["id &lt;pk&gt;", "product_id &lt;fk&gt;", "unit", "price", "stock"], 550, 100],
    "cdm_product" => ["PRODUCT", ["id &lt;pk&gt;", "category_id &lt;fk&gt;", "name", "slug", "description", "image"], 800, 100],
    "cdm_category" => ["CATEGORY", ["id &lt;pk&gt;", "name", "slug", "description"], 1050, 100]
];

foreach ($tablesCDM as $id => $data) {
    $xml .= createTable($id, $data[0], $data[1], $data[2], $data[3]);
}

$xml .= createEdge("cdm_r1", "cdm_user", "cdm_cart", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("cdm_r2", "cdm_user", "cdm_order", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("cdm_r3", "cdm_variant", "cdm_cart", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("cdm_r4", "cdm_order", "cdm_orderitem", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("cdm_r5", "cdm_variant", "cdm_orderitem", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("cdm_r6", "cdm_product", "cdm_variant", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("cdm_r7", "cdm_category", "cdm_product", "endArrow=classic;startArrow=none;", "");

$xml .= '      </root>' . "\n";
$xml .= '    </mxGraphModel>' . "\n";
$xml .= '  </diagram>' . "\n";


// ---------------------------------------------------------
// DIAGRAM 4: PDM
// ---------------------------------------------------------
$xml .= '  <diagram id="pdm" name="PDM">' . "\n";
$xml .= '    <mxGraphModel dx="1000" dy="1000" grid="1" gridSize="10" guides="1" tooltips="1" connect="1" arrows="1" fold="1" page="1" pageScale="1" pageWidth="1169" pageHeight="827" math="0" shadow="0">' . "\n";
$xml .= '      <root>' . "\n";
$xml .= '        <mxCell id="0" />' . "\n";
$xml .= '        <mxCell id="1" parent="0" />' . "\n";

$tablesPDM = [
    "pdm_user" => ["users", ["id : bigint(20) &lt;pk&gt;", "name : varchar(255)", "email : varchar(255)", "password : varchar(255)", "phone : varchar(255)", "role : enum"], 50, 300],
    "pdm_cart" => ["carts", ["id : bigint(20) &lt;pk&gt;", "user_id : bigint(20) &lt;fk&gt;", "product_variant_id : bigint(20) &lt;fk&gt;", "quantity : int(11)"], 350, 100],
    "pdm_order" => ["orders", ["id : bigint(20) &lt;pk&gt;", "user_id : bigint(20) &lt;fk&gt;", "order_number : varchar(255)", "total_price : decimal", "status : enum", "pickup_time : datetime"], 350, 500],
    "pdm_orderitem" => ["order_items", ["id : bigint(20) &lt;pk&gt;", "order_id : bigint(20) &lt;fk&gt;", "product_variant_id : bigint(20) &lt;fk&gt;", "quantity : int(11)", "price : decimal", "subtotal : decimal"], 650, 500],
    "pdm_variant" => ["product_variants", ["id : bigint(20) &lt;pk&gt;", "product_id : bigint(20) &lt;fk&gt;", "unit : varchar(255)", "price : decimal", "stock : int(11)"], 650, 100],
    "pdm_product" => ["products", ["id : bigint(20) &lt;pk&gt;", "category_id : bigint(20) &lt;fk&gt;", "name : varchar(255)", "slug : varchar(255)", "description : text", "image : varchar(255)"], 950, 100],
    "pdm_category" => ["categories", ["id : bigint(20) &lt;pk&gt;", "name : varchar(255)", "slug : varchar(255)", "description : text"], 1250, 100]
];

foreach ($tablesPDM as $id => $data) {
    $xml .= createTable($id, $data[0], $data[1], $data[2], $data[3]);
}

$xml .= createEdge("pdm_r1", "pdm_user", "pdm_cart", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("pdm_r2", "pdm_user", "pdm_order", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("pdm_r3", "pdm_variant", "pdm_cart", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("pdm_r4", "pdm_order", "pdm_orderitem", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("pdm_r5", "pdm_variant", "pdm_orderitem", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("pdm_r6", "pdm_product", "pdm_variant", "endArrow=classic;startArrow=none;", "");
$xml .= createEdge("pdm_r7", "pdm_category", "pdm_product", "endArrow=classic;startArrow=none;", "");

$xml .= '      </root>' . "\n";
$xml .= '    </mxGraphModel>' . "\n";
$xml .= '  </diagram>' . "\n";
$xml .= '</mxfile>' . "\n";

file_put_contents($filename, $xml);
echo "File $filename berhasil dibuat!\n";
