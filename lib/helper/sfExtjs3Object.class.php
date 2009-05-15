<?php
/**
 * sfExtjs3Object
 * 
 * Instances of this class can render themself as JavaScript (definitions and instances)
 *
 */
class sfExtjs3Object
{
	const FUNCTION_SEPARATOR = ' : ';
	
	protected
	 $objectName,
	 $baseObject,
	 $functions = array();
	 
	/**
	 * Creates a new JsObject
	 *
	 * @param string $name       the name of the new class to be created (can be preceded with dotted namespace
	 * @param string $base       the base object which this new class should extend
	 * @param array $functions   an associative array of function-names and the sfExtjs3Function itself 
	 */
	public function __construct($objectName, $baseObject, $functions = array())
	{
		$this->objectName = trim($objectName);
		$this->baseObject = trim($baseObject);
		
		if (!is_array($functions))
		{
			throw new Exception('argument "functions" should be an array');
		}
		foreach ($functions as $functionName => $function)
		{
			$this->addFunction($functionName, $function); 
		}
	}
	
	public function getFunctions()
	{
		return $this->functions;
	}
	
	/**
	 * Adds a function to the extjs-object
	 *
	 * @param string $functionName
	 * @param sfExtjs3Function $function
	 */
	public function addFunction($functionName, sfExtjs3Function $function)
	{
		if (isset($this->functions[$functionName]))
		{
			throw new Exception('The function "'.$functionName.'" is already defined for object "'.$this->objectName.'".');
		}
		
		$this->functions[$functionName] = $function;
	}
	
	/**
	 * returns the name of this object
	 *
	 */
	public function getName()
	{
		return $this->objectName;
	}
	
	/**
	 * renders the construction for a new instance for this object
	 *
	 * @param  string, the variable names that should be provided to the constructor
	 * @return string
	 */
	public function renderConstruction()
	{
		$args = implode(', ', func_get_args());
		
		$js  = 'new '.$this->getName().'(';
	  $js .= $args;
		$js .= ')';
		
		return $js;
	}
	
	/**
	 * Renders the calling of a function for this object
	 *
	 * @param string $functionName the function name (can be dotted for example to call superclass functions)
	 * @param array $arguments     the arguments that are called for this function
	 * @return string              the function call
	 */
	public function renderFunctionCall($functionName, $arguments = array())
	{
    if (!is_array($arguments))
    {
      throw new Exception('arguments should be an array');
    }
		
		$js  = $this->getName().'.'.$functionName.'(';
		
		$js .= implode(', ', $arguments);
		
		$js .= ');';
		
		return $js;
	}
	
	/**
	 * text representation of all functions defined for this object
	 *
	 * @return string a concatenation of of all functions surrounded by {}
	 */
	public function renderFunctionDefinitions()
	{
		$js  = '{';
		
		$functions = $this->getFunctions();
		if (count($functions))
		{
			$firstFunctionName = current(array_keys($functions));
			$firstFunction = array_shift($functions);
			
			$js .= $firstFunctionName.self::FUNCTION_SEPARATOR.$firstFunction;
			
			foreach ($functions as $functionName => $function)
			{
				$js .= ', '.$functionName.self::FUNCTION_SEPARATOR.$function;
			}
			
			$js .= ' '; 
		}
		
		$js .= '}';
		
		return $js;
	}
	
	/**
	 * renders the object definition
	 *
	 * @return string
	 */
	public function renderDefinition()
	{
		$js = $this->getName();
		
		$js .= ' = Ext.extend('.$this->baseObject.', ';
		
		$js .= $this->renderFunctionDefinitions();
		
		$js .= ');';
		
		return $js;
	}
	
	/**
	 * @see renderDefinition()
	 */
  public function __toString()
  {
    return $this->renderDefinition();
  }
   
}

?>