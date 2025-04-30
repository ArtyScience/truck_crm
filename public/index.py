import multiprocessing

def print_numbers():
    for i in range(10000000000):
        time.sleep(1)
        print(f"Process 1: {i}")

def print_letters():
    for letter in 'ABCDE':
        time.sleep(1)
        print(f"Process 2: {letter}")

# Create two processes
process1 = multiprocessing.Process(target=print_numbers)
process2 = multiprocessing.Process(target=print_letters)

# Start the processes
process1.start()
process2.start()

# Wait for both processes to finish
process1.join()
process2.join()

print("Both processes have finished.")
