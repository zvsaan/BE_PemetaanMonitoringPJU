import hashlib #sha256

# Membuat kelas Block
class Block: 
    # Konstruktor untuk kelas Block
    def __init__(self, data, prev_hash):
        self.data = data 
        self.prev_hash = prev_hash
        self.hash = self.calc_hash()  # Hash unik untuk blok ini
        
    # Metode untuk menghitung hash menggunakan algoritma SHA-256
    def calc_hash(self):
        sha = hashlib.sha256()
        sha.update(self.data.encode('utf-8'))  # Encode (hashing)
        return sha.hexdigest()  # Mengembalikan hasil hash
    

# Membuat kelas Blockchain
class Blockchain: 
    # Konstruktor untuk kelas Blockchain
    def __init__(self):
        self.chain = [self.create_genesis_block()]  # Inisialisasi blockchain dengan Genesis Block
        
    # Metode untuk membuat Genesis Block (blok pertama dalam blockchain)
    def create_genesis_block(self):
        return Block("Genesis Block", "0")  # Genesis Block memiliki data "Genesis Block" dan prev_hash = "0"
    
    # Metode untuk menambahkan blok baru ke blockchain
    def add_block(self, data):
        prev_block = self.chain[-1]  # Mendapatkan blok terakhir di blockchain
        new_block = Block(data, prev_block.hash)  # Membuat blok baru dengan data dan prev_hash dari blok terakhir
        self.chain.append(new_block)  # Menambahkan blok baru ke dalam blockchain
    

# Pengujian blockchain
blockchain = Blockchain()  # Membuat instance dari kelas Blockchain (Genesis Block otomatis dibuat)
    
# Menambahkan blok baru ke blockchain
blockchain.add_block('First block')
blockchain.add_block('Second block')
blockchain.add_block('Third block')
blockchain.add_block('Forth block') 
blockchain.add_block('Fifth block') 
blockchain.add_block('Sixth block') 
blockchain.add_block('Seventh block') 
blockchain.add_block('Eighth block') 
blockchain.add_block('Ninth block') 
blockchain.add_block('Tenth block') 

# Menampilkan isi blockchain
print('Blockchain:')
for block in blockchain.chain:  # Iterasi untuk setiap blok dalam blockchain
    print('Data:', block.data) 
    print('Previous hash:', block.prev_hash)
    print('Hash:', block.hash) 