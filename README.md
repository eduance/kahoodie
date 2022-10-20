<p align="center"><a href="https://kahoodie.io" target="_blank"><img src="https://i.ibb.co/DkTLrSC/High-Resolution-Logo-Transparent-Background.png" width="400" alt="Laravel Logo"></a></p>

## Get started easily

To get started using Kahoodie, make sure you have Docker installed locally. 
If you do, make sure to follow these steps in the right order.

(1) Copy the .env.example and rename it to .env

(2) Run ```./vendor/bin/sail up```

(3) Run ```./vendor/bin/sail artisan key:generate``` to create an application key.

(4) Run ```./vendor/bin/sail artisan flashcard:interactive``` to boot Kahoodie.

I have left all the branches for you to have a look inside at each stage of the process.

## Test it!

Run ```./vendor/bin/sail test``` to run the testsuite.

## Database structure

```
questions | answers     | attempts   
id        | id          | id          
question  | question_id | question_id
status    | text        | correct
                        | answer
```

This is the database structure I decided to choose after going through all the normalization steps.
I separated the questions from the answers because questions and answers have a direct relationship.

A separate table named attempts takes care of all of our sent-in answers; this is so that we could eventually
attach a user to who made the attempt.

## About Kahoodie

Kahoodie is the coolest flash card app available on Github. As you might see, Kahoodie is an overcomplicated
project built with a test-driven-development fashion, but focuses mainly on showcasing the developers skill and
love for Laravel.

## Dependencies

I utilized spatie/laravel-DTOs because I want to avoid having to guess what's inside my array. My actions will
always take in a DTO.

## Deeper insights

The inventor of C++, Bjarne Stroupstrup once said:

"I like my code to be elegant and effecient. The logic should be straightforward to make it
hard for bugs to hide, the dependencies minimal to ease maintenance, error handling complete
according to an articulated strategy, and performance close to optimal so not tempt people
to make the code messy with unprincipled optimizations. Clean code does one thing well."

* Readibility

This is my school of thought, as Robert C. Martin has stated before, the @author doc notation tells us
that we are authors and as authors have readers, we are responsible for communicating well with our readers.

* Testing

I used a test-driven-design approach and made sure that my tests are fast, so I would keep wanting to run them over and over if deemed necessary. Imagine having
slow tests and your colleagues don't want to run them as fast. By making my tests independent every test can be singled out,
so we can avoid having a complicated analysis of what's going wrong where and when. Repeatable is also a pretty important
keyword, it means that it should be repeatable in every environment. As I'm working on a Linux laptop I would
want my colleagues using a macbook also be able to run them. We can't all have a good operating system! (i'm kidding <3)
My tests also are self-validating: they pass, or they fail and last but not least: they should be written just before
the production code that makes them pass, this makes them written in a timely fashion.

All with all, my tests are based upon the F.I.R.S.T. principle.

* Datastructures

Yes, I love PHP. Yes I love Laravel. No, I won't use arrays (or Collections) to solve all my problems.

"Smart datastructures and dumb code works a lot better than the other way around."

PHP has a set of pretty cool, yet sadly hidden datastructures which can be really useful.
A lot of companies tend to want to avoid those due to developers not being trained enough.
I personally think datastructures can make your life much easier when used in the right setting.

## About the creator

Well, there I am. I'm Brandon and I can't lie, I had a lot of fun building this one. I have overcomplicated
some stuff, not to flex but rather to show how comfortable I am with Laravel, but that's the idea behind this assignment.
I am 23 years old and have been coding for over 10 years and have been loving it so far. The current app you're looking at is a tad different than the one you were
supposed to receive, but I didn't persist due to.. 'overcomplications' so to speak. Well, enjoy and and feel free to
reach out to ask any questions.

You can contact me at brandon@eduance.io or my phone number +31657769941.
