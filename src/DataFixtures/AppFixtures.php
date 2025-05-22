<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Expense;
use App\Entity\Budget;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // 1. Categories
        $categories = [];
        $categoryNames = ['Groceries', 'Transport', 'Entertainment', 'Utilities', 'Health', 'Dining Out', 'Education'];
        $expenseTitle = [
            'Groceries', 'Rent', 'Utilities', 'Dining Out', 'Transportation',
            'Gas', 'Internet', 'Mobile Plan', 'Streaming Services',
            'Gym Membership', 'Insurance', 'Medical', 'Clothing', 'Travel',
            'Entertainment', 'Subscriptions', 'Education', 'Coffee', 'Snacks'
        ];
        
        shuffle($categoryNames);
        shuffle($expenseTitle);

        foreach (array_slice($categoryNames, 0, rand(5, 7)) as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setColor('#000000');
            $manager->persist($category);
            $categories[] = $category;
        }

        // 2. Expenses
        for ($i = 0; $i < 60; $i++) {
            $expense = new Expense();
            $expense->setName($faker->randomElement($expenseTitle));
            $expense->setAmount($faker->randomFloat(2, 5, 150));
            $expense->setDate($faker->dateTimeBetween('-30 days', 'now'));
            $expense->setCategory($faker->randomElement($categories));
            $manager->persist($expense);
        }

        // 3. Budgets per category
        foreach ($categories as $category) {
            $budget = new Budget();
            $budget->setCategory($category);
            $budget->setMonthlyLimit($faker->randomFloat(2, 100, 1000));
            $manager->persist($budget);
        }

        $manager->flush();
    }
}
