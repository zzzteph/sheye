while [ true ]
do

  queues=$(ps aux| grep -v grep| grep "queue:work database --queue=listeners" | wc -l);
  if [ "$queues" -lt "1" ]; then

   php artisan queue:work database --queue=listeners --sleep=3 > notify.log &
  fi


 queues=$(ps aux| grep -v grep| grep "queue:work database --queue=discovery" | wc -l);
  if [ "$queues" -lt "3" ]; then

   php artisan queue:work database --queue=discovery --sleep=3 > discovery.log &
  fi
 queues=$(ps aux| grep -v grep| grep "queue:work database --queue=resource" | wc -l);
  if [ "$queues" -lt "5" ]; then

   php artisan queue:work database --queue=resource --sleep=3 > resource.log &
  fi

 queues=$(ps aux| grep -v grep| grep "queue:work database --queue=analyze" | wc -l);
  if [ "$queues" -lt "2" ]; then

   php artisan queue:work database --queue=analyze --sleep=3 > resource.log &
  fi




	php artisan schedule:run




  sleep 60
done
