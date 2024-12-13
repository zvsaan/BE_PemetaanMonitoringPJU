
import hashlib 

class NeuralCoinBlock: 
    def __init__(self, previous_block_hash, transaction_list):
        self.previous_block_hash = previous_block_hash
        self.transaction_list = transaction_list
        
        self.block_data = "-".join(transaction_list) + "_" + previous_block_hash
        self.block_hash = hashlib.sha256(self.block_data.encode()).hexdigest()
        
t1 = "Bambang kirim 2 Coin ke Bagus"
t2 = "Farhan kirim 3.1 Coin ke Lisa"
t3 = "Prabowo kirim 2 Coin ke Saprol"
t4 = "Sugeng kirim 2 Coin ke Farhan"
t5 = "Lisa kirim 2 Coin ke Prabowo"
t6 = "Saprol kirim 2 Coin ke Bambang"
t7 = "Bambang kirim 2 Coin ke Bagus"
t8 = "Farhan kirim 3.1 Coin ke Lisa"


initial_block = NeuralCoinBlock("Initial String", [t1, t2])

initial_block2 = NeuralCoinBlock("Transactional 2",[t7,t8])

print(initial_block.block_data)
print(initial_block.block_hash)

print("Block 2")
print(initial_block2.block_data)
print(initial_block2.block_hash)



