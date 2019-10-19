# Use Cases

[UC1 - UC4](https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/UseCases.md)

***

## UC5 - Start Hangman

### __Precondition__

UC1 or UC3. A user is authenticated.

### __Main Scenario__

1. Starts when a user wants to play Hangman.
2. The System presents Hangman.

***

## UC6 - Play Hangman

### __Precondition__

UC5 - A user wants to start Hangman.

### __Main Scenario__

1. A user starts guessing a letter.
2. The system evaluates the input and presents the letter inside the word and adds the letter to guessed letters.
3. Step 1 until the whole word is guessed.
4. The System presents a win message and a restart button.

### __Alternate Scenarios__

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2a. The guessed letter is wrong and the stick figure begins to get hung.  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2b. The guessed input does not contain one single letter and the System presents an error message.  
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4a. The word is not guessed when the stick figure is hung and the System presents a lose message and a restart button.  

## UC7 - Restart Hangman

### __Precondition__

UC6 - The game is won or lost.

### __Main Scenario__

1. A user clicks the restart button.
2. The System restarts the game.

***

# Test Cases

[TC1-TC4](https://github.com/dntoll/1dv610/blob/master/assignments/A2_resources/UseCases.md)

***

## Test case 5.1: Start Hangman
Make sure Hangman is started when logging in.

### __Input__

* Test case 1.7

### __Output__

* Test case 1.7
* A hanging pole is shown.
* A word to be guessed displayed as underscores is shown.
* The text "Guessed letters" is shown.
* An input field with a button with the text "Guess" is shown.

![Test case 5.1 output][5.1]

***

## Test case 6.1: Play Hangman with correct letter input

Make sure Hangman presents a correct inputed letter.

### __Input__

* Test case 5.1
* Enter a letter and press guess.

### __Output__

* One or more letters in the word displayed as underscores will be replaced with the inputed letter.
* The letter guessed is shown below "Guessed letters".

![Test case 6.1 output][6.1]

***

## Test case 6.2: Play Hangman with wrong letter input

Make sure Hangman presents wrong inputed letter.

### __Input__

* Test case 5.1
* Enter a letter and press guess.

### __Output__

* The hanging pole is updated and the stick figure is beginning to get hung.
* The letter guessed is shown below "Guessed letters".

![Test case 6.2 output][6.2]

***

## Test case 6.3: Play Hangman and win

Make sure Hangman presents a win when guessing the right word.

### __Input__

* Test case 6.1 until the whole word is displayed.

### __Output__

* The correct word is shown.
* The text "You win! You had 8 attempts left!" is shown.
* The letters guessed are shown below "Guessed letters".
* A restart button is shown.

![Test case 6.3 output][6.3]

***

## Test case 6.4: Play Hangman and lose

Make sure Hangman presents a lose when guessing the wrong word.

### __Input__

* Test case 6.2 until the stick figure is hung.

### __Output__

* A hung stick figure is shown.
* The text "Sorry, you lost. The correct word is TheCorrectWord." is shown.
* The letters guessed are shown below "Guessed letters".
* A restart button is shown.

![Test case 6.4 output][6.4]

***

## Test case 6.5: Play Hangman and enter an invalid character

Make sure Hangman presents an error message when inputing an invalid character.

### __Input__

* Test case 5.1
* Enter the number 123 and click "Guess"

### __Output__

* Test case 5.1 and the text "Only one letter is allowed." is shown.

![Test case 6.5 output][6.5]

***

## Test case 6.6: Play Hangman and enter an already guessed letter

Make sure Hangman presents an error message when inputing an already guessed letter.

### __Input__

* Test case 5.1
* Enter the letter A and press "Guess".
" Enter the letter A again and press "Guess".

### __Output__

* Test case 5.1 and the text "Letter has already been guessed.", along with the text "a," below "Guessed letters" is shown.

![Test case 6.6 output][6.6]

***

## Test case 7.1: Restart Hangman

Make sure Hangman restarts the game.

### __Input__

* Test case 6.3 or 6.4.
* Click the button "Restart".

### __Output__

* Test case 5.1 is shown.

![Test case 6.7 output][5.1]

***

[5.1]: /documentation/5.1.png
[6.1]: /documentation/6.1.png
[6.2]: /documentation/6.2.png
[6.3]: /documentation/6.3.png
[6.4]: /documentation/6.4.png
[6.5]: /documentation/6.5.png
[6.6]: /documentation/6.6.png
