<?php
/**
 * sfExtjs3Function
 * 
 * Instances of this class can render themself as JavaScript functions
 *
 */
class sfExtjs3Function
{
  protected
   $arguments,
   $content;
   
  /**
   * Creates a new JsObject
   *
   * @param array $arguments    
   * @param string $content     
   */
  public function __construct($arguments, $content)
  {
    if (!is_array($arguments))
    {
      throw new Exception('arguments should be an array');
    }
  	
  	$this->arguments = $arguments;
  	$this->content   = $content;
  }
  
  /**
   * Renders this function as a js-text
   *
   * @return string the js-representation of this function
   */
  public function render()
  {
  	$js  = 'function(';
  	
  	$js .= implode(', ', $this->arguments);
  	
  	$js .= ') {';
  	
  	$js .= $this->content; 
  	
  	$js .= '}';
  	
  	return $js;
  }
  
  /**
   * @see render()
   */
  public function __toString()
  {
  	return $this->render();
  }
  
}

?>