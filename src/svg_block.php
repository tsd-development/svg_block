<?php


namespace topshelfdesign;

class svg_block
{

    function __construct()
    {
        $this->lines = [];
        $this->width = 130;
        $this->height = 70;
        $this->line_height = 15;
        $this->y_start = 15;
        $this->font_weight = 800;
        $this->ID = "svg-" . rand(0, 9999);
        $this->box_offset = 2;
        $this->percent_width = 100;
        $this->class= '';
    }

    public function add_class($class){
        $this->class .= "$class ";
    }

    public function set_offset($offset){
        $this->box_offset = $offset;
    }

    public function set_weight($weight)
    {
        $this->font_weight = $weight;
    }

    public function set_width($percentage)
    {
        $this->percent_width = $percentage;
    }

    public function add_line($text)
    {
        $this->lines[] = $text;
    }

    protected function calculate_viewbox_dimensions()
    {
        $this->height = $this->line_height * count($this->lines) + ($this->box_offset * 2);
    }

    public function output_svg()
    {

        $text = '';

        foreach ($this->lines as $c => $line):
            $y_pos = $c * $this->line_height + $this->y_start;
            $text .= "<text class='the-text' x='$this->box_offset' y='$y_pos'>$line</text>";
        endforeach;

        $this->calculate_viewbox_dimensions();


        print "

              <svg version='1.1'
              id='{$this->ID}'
               xmlns='http://www.w3.org/2000/svg'
               xmlns:xlink='http://www.w3.org/1999/xlink'
               style='width: $this->percent_width%;'
               preserveAspectRatio='xMidYMid meet'
               class='$this->class'
               xml:space='preserve'
               >
                <style>
                  #$this->ID .the-text {
                      font-family: \"open sans\";
                      font-weight: {$this->font_weight};
                  }
                </style>
                $text
              </svg>

              <script>

              var svg = $('#{$this->ID}'),
              textElm = svg.find('.the-text');


              textElm.each(function(){

                  var SVGRect = $(this)[0].getBBox();
                  var offset = $this->box_offset;


                  var coord = {
                    x: SVGRect.x - offset,
                    y: SVGRect.y + offset,
                    width: SVGRect.width + (offset * 2),
                    height: SVGRect.height - offset
                  };


                  var rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                  rect.setAttribute('x', coord.x);
                  rect.setAttribute('y', coord.y);
                  rect.setAttribute('width', coord.width);
                  rect.setAttribute('height', coord.height);
                  rect.setAttribute('fill', 'white');

                  svg.prepend(rect);

              });

              var rect = svg.find('rect');
              var bounds = {
                  x: new Array(),
                  y: new Array(),
                  height: new Array(),
                  width: new Array()
              };

              rect.each(function(){

                  var coords = $(this)[0].getBBox();
                  bounds.x.push(coords.x);
                  bounds.y.push(coords.y);
                  bounds.width.push(coords.width);
                  bounds.height.push(coords.height);

              });


              bounds.x = {min: Math.min.apply(Math, bounds.x), max: Math.max.apply(Math, bounds.x)};
              bounds.y = {min: Math.min.apply(Math, bounds.y), max: Math.max.apply(Math, bounds.y)};
              bounds.width = Math.max.apply(Math, bounds.width);
              bounds.height = Math.max.apply(Math, bounds.height);
              bounds.vx = 0;
              bounds.vy = bounds.y.min;
              bounds.vw = bounds.width;
              bounds.vh = Math.abs(bounds.y.min) + bounds.y.max + bounds.height;
              console.log(bounds.vw);

              svg[0].setAttribute('viewBox', '0 ' + bounds.vy + ' ' + bounds.vw + ' ' + bounds.vh);


              </script>


          ";


    }


}

?>
