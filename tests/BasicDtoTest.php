<?php

use Faker\Factory;

require 'basic/UserDto.php';

class BasicDtoTest extends PHPUnit_Framework_TestCase
{
    public function generateUsers()
    {
        $faker = Factory::create();
        $users = [];
        for ($i = 0; $i <= 50; $i++) {
            $users[] = [
                $faker->userName,
                $faker->email,
                $faker->name,
                $faker->password,
            ];
        }

        return $users;
    }

    /**
     * @param $userName
     * @param $email
     * @param $name
     * @param $password
     *
     * @dataProvider generateUsers
     */
    public function testPropertiesAndSetters($userName, $email, $name, $password)
    {
        $model = new UserDto($userName, $email, $name, $password);
        $model->setPassword($password);

        $this->assertEquals($userName, $model->getUserName());
        $this->assertEquals($email, $model->getEmail());
        $this->assertEquals($name, $model->getName());
        $this->assertNotEquals($password, $model->getPassword());
        $this->assertEquals(sha1($password), $model->getPassword());
    }

    /**
     * @param $userName
     * @param $email
     * @param $name
     * @param $password
     *
     * @dataProvider generateUsers
     */
    public function testSerialization($userName, $email, $name, $password)
    {
        $model = new UserDto($userName, $email, $name, $password);
        $model->setPassword($password);

        $array = $model->toArray();

        $this->assertArrayHasKey('userName', $array);
        $this->assertArrayHasKey('email', $array);
        $this->assertArrayHasKey('name', $array);

        /*
         * Private properties should not be serialized
         */
        $this->assertArrayNotHasKey('password', $array);

        $this->assertEquals($userName, $array['userName']);
        $this->assertEquals($email, $array['email']);
        $this->assertEquals($name, $array['name']);
    }
}