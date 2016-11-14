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

              (function($) {
              $.fn.svgBlock = function(params) {

                var obj = $(this);
                var lines = $(this).find('text');
                var line_height = params.line_height ? params.line_height : 15;
                var offset = params.offset ? params.offset : 0;
                var percent_width = params.percent_width ? params.percent_width : 100;
                var color = params.color ? params.color : 'black';
                var background = params.background ? params.background : 'white';
                var left_offset = typeof(params.left_offset) !== 'undefined' ? params.left_offset : true;

                lines.each(function(key, value){


                  var SVGRect = $(this)[0].getBBox();

                  var line_y_pos = (key + 1) * SVGRect.height;
                  var rect_y_pos = key * SVGRect.height;

                  var line_x_pos = offset;


                  $(this)
                    .attr('fill', color)
                    .attr('y', line_y_pos)
                    .attr('x', line_x_pos);

                  var coord = {
                    x: 0,
                    y: rect_y_pos + (.3 * offset),
                    width: SVGRect.width + (offset * 2),
                    height: SVGRect.height + offset
                  };

                  console.log(coord, SVGRect);

                  var rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
                  rect.setAttribute('x', coord.x);
                  rect.setAttribute('y', coord.y);
                  rect.setAttribute('width', coord.width);
                  rect.setAttribute('height', coord.height);
                  rect.setAttribute('fill', background);

                  obj.prepend(rect);

                });


                var rect = obj.find('rect');
                var bounds = {
                  x: [],
                  y: [],
                  height: [],
                  width: []
                };

                rect.each(function() {
                  var coords = $(this)[0].getBBox();
                  bounds.x.push(coords.x);
                  bounds.y.push(coords.y);
                  bounds.width.push(coords.width);
                  bounds.height.push(coords.height);
                });

                bounds.x = {
                  min: Math.min.apply(Math, bounds.x),
                  max: Math.max.apply(Math, bounds.x)
                };

                bounds.y = {
                  min: Math.min.apply(Math, bounds.y),
                  max: Math.max.apply(Math, bounds.y)
                };
            
                bounds.width = Math.max.apply(Math, bounds.width);
                bounds.height = Math.max.apply(Math, bounds.height);
                bounds.vx = left_offset ? offset : 0;
                bounds.vy = bounds.y.min;
                bounds.vw = left_offset ? bounds.width : bounds.width - offset;
                bounds.vh = Math.abs(bounds.y.min) + bounds.y.max + bounds.height;


                obj[0].setAttribute('viewBox', bounds.vx + ' ' + bounds.vy + ' ' + bounds.vw + ' ' + bounds.vh);
                obj[0].setAttribute('width', percent_width + '%');

                return this;

              };
            })(jQuery);


            $('#{$this->ID}').svgBlock({
              offset: 3,
              left_offset: true
            });
            </script>

          ";


    }


}

?>
