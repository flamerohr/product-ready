# Product Ready App

## Starting local development

A few things to make sure you have ready before you can begin running this app in your local machine:
- Make sure to have [Docker Desktop](https://www.docker.com/) installed
- For `Windows`, make sure you have WSL2 installed and enabled, [Microsoft's developer environment documentation](https://docs.microsoft.com/en-us/windows/wsl/install-win10) has more details.

When you're all setup, run:
```
./vendor/bin/sail up
```
Then browse to http://localhost to view the app.

**NOTE**: The first time you run the `Sail up` command, Sail's application containers will be built on your machine. This could take several minutes. **Don't worry, subsequent attempts to start Sail will be much faster.**

## Not ready for production

This was built without the intention for this to be ready for production.

## Instructions

Your mission, should you choose to accept it, is to write a Laravel application that helps a user understand how much quantity of a product is available for use.

The application should display an interface with a button and a single input that represents the requested quantity of a product.

When the button is clicked, the interface should show either the $ value of the quantity of that product that will be applied, or an error message if the quantity to be applied exceeds the quantity on hand.

Note that product purchased first should be used first, therefore the quantity on hand should be the most recently purchased.

A csv file is attached that you should use as your data source.

Here is a small example of inventory movements:  
a. Purchased 1 unit at $10 per unit  
b. Purchased 2 units at $20 per unit  
c. Purchased 2 units at $15 per unit  
d. Applied 2 units  

After the 2 units have been applied, the purchased units in 'a' have been completely used up. Only 1 unit from 'b' has been used, so the remaining inventory looks like this:

b. 1 unit at $20 per unit c. 2 units at $15 per unit  
Quantity on hand = 3 Valuation = (1 * 20) + (2 * 15) = $50

Here's what we'll be looking for in your submission:
	• Breaking the code down into logical classes and methods that have a single responsibility
	• Clear comments that explain the code logic
	• Descriptive method and variable names
	• Usage of suitable packages (if applicable) to solve a problem rather than writing code from scratch. No need to reinvent the wheel :)
	• A descriptive README file
	• Some PHPUnit tests to ensure other developers don't accidentally break 

