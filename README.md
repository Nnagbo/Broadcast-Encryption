
# Identity-Based Broadcast Encryption System

This is a system that allows a broadcaster to send encrypted messages to a set of users. These users subscribe to the channel and their private identities are used to encrypt messages that can be sent to them. The eligible users, in turn use their private keys to decrypt the broadcast message.
## Authors

- Obi-Nnagbo Somkene Gerald
- Vincent Daniel
- Iwuxhukwu Vitalis
## Contributing

Contributions are always welcome!

See `contributing.md` for ways to get started.

Please adhere to this project's `code of conduct`.


## Deployment

To deploy this project run

- Upload the SQL database called "mail-cryptography"
- Run mail-cryptography/public/


## Documentation


Run the local host, for example XAMPP

Upload the database or create a database with the name "mail-cryptography"

Run 'localhost/mail-cryptography/public'

Use 'admin@mmail.com' as admin username and '123456' as password


## Environment Variables

To run this project, you will need to add the following environment variables/packages to your .env file

'Controller'
## FAQ

#### Does this work online for now?

No. It has not been deployed online yet. It runs on a localhost

#### Do I need to register before using the system?

Yes, any new user must register to be an eligible subscriber.




## Features

- Shows encryption time
- Fast encryption and decryption
- Broadcast and personal messaging
- AES encrypted


## Feedback

If you have any feedback, please reach out to us at somkenennagbo@gmail.com


## 🚀 About Me
I'm a full stack and web developer developer with interest in HTML, CSS, Javascript, PHP and node.js. I am looking at diving into Python and other object oriented languages in due course. 

Hello.

My name is Obi-Nnagbo Somkene, a web developer and programming. I am a Nigerian who is passionate about creativity, positivity and analytics. 
## 🛠 Skills
Javascript, HTML, CSS, Java, node.js, PHP


## Tech Stack

**Client: HTML, CSS, Javascript

**Server: PHP


use DB;
use App\User;
use App\Libraries\Custom;
use App\Libraries\Transactions;


class AdminController extends Controller
{
    protected $custom;
    protected $transactions;

    protected $site_name;
    protected $live_server;

    public function __construct()
    {
        $this->middleware('clogin');
        $this->custom = new Custom();
        $this->transactions = new Transactions();

        $this->live_server = env('APP_URL');
        $this->site_name = env('SITE_NAME');
    }


## Used By

This project is used by the following companies/individuals:

- Special content providers
- Network providers
- Digitial signature companies
- Digital content providers



    
