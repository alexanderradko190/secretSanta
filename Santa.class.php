<?php

class SecretSanta
{
    public function run($users_array)
    {
        $ok = $this->validateArray($users_array);
        if (!$ok) {
            return false;
        }
        $matched = $this->assign_users($users_array);
        $this->getGiverNames($matched);
        return true;
    }

    private function validateArray($users_array)
    {

        if (sizeof($users_array) < 2) {
            echo 'Ошибка, указан только 1 участник. Должно быть не менее 2 участников.';
            return false;
        }

        $tmp_emails = array();
        foreach ($users_array as $u) {
            if (in_array($u['email'], $tmp_emails)) {
                echo "Ошибка, пользователи не могут пересекаться более 1 раза.";
                return false;
            }
            $tmp_emails[] = $u['email'];
        }
        return true;
    }

    public function assign_users($users_array)
    {
        $givers = $users_array;
        $receavers = $users_array;

        foreach ($givers as $uid => $user) {
            $not_assigned = true;

            while ($not_assigned) {

                $choice = rand(0, sizeof($receavers) - 1);

                if ($user['email'] !== $receavers[$choice]['email']) {

                    $givers[$uid]['giving_to'] = $receavers[$choice];

                    unset($receavers[$choice]);

                    $receavers = array_values($receavers);

                    $not_assigned = false;
                } else {

                    if (sizeof($receavers) == 1) {

                        $givers[$uid]['giving_to'] = $givers[0]['giving_to'];
                        $givers[0]['giving_to'] = $givers[$uid];
                        $not_assigned = false;
                    }
                }
            }
        }

        return $givers;
    }

    private function getGiverNames($assigned_users)
    {
        foreach ($assigned_users as $giver) {
            $this->sent_names[] = $giver['name'];
        }
    }

    public function getNames()
    {
        return $this->sent_names;
    }
}