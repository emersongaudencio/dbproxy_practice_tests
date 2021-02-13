<?php
// descriptor array
$desc = array(
  0 => array('pipe', 'r'), // 0 is STDIN for process
  1 => array('pipe', 'w'), // 1 is STDOUT for process
  2 => array('file', '/tmp/error-output.txt', 'a') // 2 is STDERR for process
);
// List of arguments
$arguments = [
  '500_proxysql_dbtest_session00.php',
  '501_proxysql_dbtest1_session01.php',
  '502_proxysql_dbtest1_session02.php',
  '503_proxysql_dbtest2_session02.php',
];
$processes = [];
$running = TRUE;
$job_count = count($arguments); // Number of jobs we need to run.
$jobs_remaining = $job_count;
$threads = 4; // Number of jobs to run simultaneously.
$job_num = 0;
while ($running) {
  for ($i = 0; $i < $threads; $i++) {
    if ($jobs_remaining > 0 && !isset($processes[$i])) {
      $argument = $arguments[$job_num];
      $cmd = 'php "' . $argument . '"'; // Pass $argument to child.php so it can process on it.
      print(" -> Process $i: Started");
      print("\n-------\n");
      $p = proc_open($cmd, $desc, $pipes);
      $processes[$i] = array(
        'process' => $p,
        'pipes' => $pipes,
      );
      $jobs_remaining -= 1;
      $job_num++;
    }
  }
  for ($i = 0; $i < $threads; $i++) {
    if (!isset($processes[$i])) {
      continue;
    }
    $process_running = _monitor_process($i, $processes[$i]['process'], $processes[$i]['pipes']);
    // Check if a process is in running state or not. If not then unset the prcess from $processes array to store a new process resource.
    if (!$process_running) {
      unset($processes[$i]);
      print("Process $i finished.");
      print("\n-------\n");
    }
  }
  if ($jobs_remaining < 1 && empty($processes)) {
    $running = FALSE;
  }
}
function _monitor_process($thread_id, $process, $pipes) {
  $status = proc_get_status($process);
  foreach ($pipes as $id => $pipe) {
    if ($id == 0) {
      // Don't read from stdin!
      continue;
    }
    $messages = stream_get_contents($pipe);
    if (!empty($messages)) {
      foreach (explode("\n", $messages) as $message) {
        $message = trim($message);
        if (!empty($message)) {
          print(" -> Process $thread_id message: $message");
          print("\n-------\n");
        }
      }
    }
  }
  if (!$status['running']) {
    foreach ($pipes as $pipe) {
      fclose($pipe);
    }
    proc_close($process);
  }
  return $status['running'];
}
?>
